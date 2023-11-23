<?php namespace Piwik\Plugins\Seven\SMSProvider;

use Exception;
use Piwik\Http;
use Piwik\Plugins\MobileMessaging\APIException;
use Piwik\Plugins\MobileMessaging\SMSProvider;
use Piwik\Piwik;

/** Add Seven to SMS providers */
class Seven extends SMSProvider {
    const SOCKET_TIMEOUT = 15;

    /** @return string */
    public function getId() {
        return 'Seven';
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
            Piwik::translate('Seven_HowTo', '
            <a href=\'https://www.seven.io\' rel=\'noreferrer noopener\' target=\'_blank\'>
                <img alt=\'seven\' src=\'plugins/Seven/images/Seven.png\' width=\'220\'/>
            </a>
            '),
            Piwik::translate('Seven_HowTo1'),
            Piwik::translate('Seven_HowTo2'),
            Piwik::translate('Seven_HowTo3'),
            Piwik::translate('Seven_About'),
            Piwik::translate('Seven_About1'),
            Piwik::translate('Seven_About2'),
            Piwik::translate('Seven_About3'),
        );
    }

    /** @return string[][] */
    public function getCredentialFields() {
        return [
            [
                'name' => 'apiKey',
                'title' => 'Seven_ApiKey',
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
            throw new APIException(Piwik::translate('Seven_ApiKeyMissing'));
        }

        return 100 == $this->sms(
                $credentials,
                'HI2U',
                '+490123456789',
                'Matomo',
            );
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private function sms(array $credentials, string $text, string  $to, ?string $from) {
        return $this->request(
            'POST',
            'sms',
            $credentials,
            compact('from', 'text', 'to')
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
            throw new APIException(Piwik::translate('Seven_ApiKeyError'));
        }

        return Piwik::translate('MobileMessaging_Available_Credits', array($credits . ' €'));
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private function request(
        string $method,
        string $endpoint,
        array $credentials,
        ?array $body = []
    ) {
        $isGET = 'GET' === strtoupper($method);
        $apiKey = $credentials['apiKey'];
        $url = 'https://gateway.seven.io/api/' . $endpoint;

        if ($isGET) {
            $query = http_build_query($body);
            $url .= '?' . $query;
            $body = [];
        }

        return Http::sendHttpRequestBy(
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
            $body,
            [
                'SentWith: Matomo',
                'X-Api-Key: ' . $apiKey,
            ]
        );
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
