<?php
/**
 * Matomo - free/libre analytics platform
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\Sms77;

class Sms77 extends \Piwik\Plugin {
    /**
     * @see \Piwik\Plugin::registerEvents
     */
    public function registerEvents()
    {
        return [
            'Translate.getClientSideTranslationKeys' => 'getClientSideTranslationKeys',
        ];
    }

    public function getClientSideTranslationKeys(&$translationKeys) {
        $translationKeys[] = 'Sms77_ApiKey';
    }
}
