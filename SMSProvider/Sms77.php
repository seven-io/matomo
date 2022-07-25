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

/** Add Sms77 to SMS providers */
class Sms77 extends SMSProvider {
    const SOCKET_TIMEOUT = 15;

    /** @return string */
    public function getId() {
        return 'Sms77';
    }

    /** @return string */
    public function getDescription() {
        return 'You can use <a target="_blank" rel="noreferrer noopener" href="https://sms77.io"><img src="plugins/Sms77/images/Sms77.png"/></a> to send SMS Reports from Matomo.<br/>
			<ul>
			<li>Sign up at Sms77.io - registration is free and non-binding</li>
			<li>Copy the API key from the your dashboard</li>
			<li>Enter the API Key on this page</li>
			</ul>
			<br/>About Sms77.io:
			<ul>
			<li>Sending millions of SMS since 2003</li>
			<li>High reliability at low cost</li>
			<li>Secure operations in Germany based data centers</li>
			</ul>
			';
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
            throw new APIException('API key can not be empty.');
        }

        return 100 == $this->sms(
                $credentials, 'HI2U', '+490123456789', 'Matomo', true);
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
        return $this->request('POST', 'sms', $credentials,
            compact('debug', 'from', 'text', 'to'));
    }

    /**
     * @param array $credentials Array containing credentials
     * @return string
     * @throws Exception
     */
    public function getCreditLeft($credentials) {
        return $this->request('GET', 'balance', $credentials) . ' €';
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
        $sentWith = 'matomo';
        $isGet = 'GET' === strtoupper($method);
        $apiKey = $credentials['apiKey'];
        $body['p'] = $apiKey;
        $body['sentWith'] = $sentWith;
        $url = "https://gateway.sms77.io/api/$endpoint";

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
        $this->sms($credentials, $text, $to, $from);
    }
}