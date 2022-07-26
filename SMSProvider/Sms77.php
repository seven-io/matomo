<?php
/**
 * Matomo - free/libre analytics platform
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\Sms77\SMSProvider;

use Exception;
use Piwik\Http;
use Piwik\Plugins\MobileMessaging\APIException;
use Piwik\Plugins\MobileMessaging\SMSProvider;
use Piwik\Piwik;

/** Add Sms77 to SMS providers */
class Sms77 extends SMSProvider {
    const SOCKET_TIMEOUT = 15;

    /** @return string */
    public function getId() {
        return 'Sms77';
    }

    /** @return string */
    public function getDescription() {
        return sprintf('
            %s
            <br/>
            <ul>
                <li>%s</li>
                <li>%s</li>
                <li>%s</li>
            </ul>
            <br/>
            %s
            <ul>
                <li>%s</li>
                <li>%s</li>
                <li>%s</li>
            </ul>
        ',
            Piwik::translate('Sms77_HowTo', '
            <a href=\'https://www.sms77.io\' rel=\'noreferrer noopener\' target=\'_blank\'>
                <img alt=\'\' src=\'plugins/Sms77/images/Sms77.png\'/>
            </a>
            '),
            Piwik::translate('Sms77_HowTo1'),
            Piwik::translate('Sms77_HowTo2'),
            Piwik::translate('Sms77_HowTo3'),
            Piwik::translate('Sms77_About'),
            Piwik::translate('Sms77_About1'),
            Piwik::translate('Sms77_About2'),
            Piwik::translate('Sms77_About3'),
        );
    }

    /** @return string[][] */
    public function getCredentialFields() {
        return [
            [
                'name' => 'apiKey',
                'title' => 'Sms77_ApiKey',
                'type' => 'text',
            ],
        ];
    }

    /**
     * @param array $credentials Array containing credentials
     * @return bool
     * @throws APIException
     */
    public function verifyCredential($credentials) {
        if (!isset($credentials['apiKey'])) {
            throw new APIException(Piwik::translate('Sms77_ApiKeyMissing'));
        }

        return 100 == $this->sms(
                $credentials,
                'HI2U',
                '+490123456789',
                'Matomo',
                true
            );
    }

    /**
     * @param array $credentials Array containing credentials
     * @param string $text The actual message content
     * @param string $to The recipient(s) separated by comma
     * @param string|null $from Optional caller ID
     * @param boolean $debug Don't send out messages
     * @return mixed
     * @throws Exception
     */
    private function sms($credentials, $text, $to, $from, $debug = false) {
        $debug = intval($debug);

        return $this->request(
            'POST',
            'sms',
            $credentials,
            compact('debug', 'from', 'text', 'to')
        );
    }

    /**
     * @param array $credentials Array containing credentials
     * @return string
     * @throws Exception
     */
    public function getCreditLeft($credentials) {
        $credits = $this->request(
            'GET',
            'balance',
            $credentials
        );

        if (!is_numeric($credits)  || strpos($credits, '.') === false) {
            throw new APIException(Piwik::translate('Sms77_ApiKeyError'));
        }

        return Piwik::translate('MobileMessaging_Available_Credits', array($credits . ' €'));
    }

    /**
     * @param string $method HTTP method
     * @param string $endpoint API endpoint
     * @param array $credentials Array containing credentials
     * @param array|null $body Optional request body
     * @return mixed
     * @throws Exception
     */
    private function request($method, $endpoint, $credentials, $body = []) {
        $isGet = 'GET' === strtoupper($method);
        $apiKey = $credentials['apiKey'];
        $body['p'] = $apiKey;
        $body['sentWith'] = 'matomo';
        $url = 'https://gateway.sms77.io/api/' . $endpoint;

        if ($isGet) {
            $query = http_build_query($body);
            $url .= '?' . $query;
            $body = [];
        }

        $res = Http::sendHttpRequestBy(
            Http::getTransportMethod(),
            $url,
            self::SOCKET_TIMEOUT,
            null,
            null,
            null,
            0,
            false,
            false,
            false,
            false,
            $method,
            null,
            null,
            $body
        );

        return $res;
    }

    /**
     * @param array $credentials Array containing credentials
     * @param string $text The actual message content
     * @param string $to The recipient(s) separated by comma
     * @param string|null $from Optional caller ID
     * @throws Exception
     */
    public function sendSMS($credentials, $text, $to, $from) {
        $this->sms(
            $credentials,
            $text,
            $to,
            $from
        );
    }
}