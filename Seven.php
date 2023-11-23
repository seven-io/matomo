<?php namespace Piwik\Plugins\Seven;

class Seven extends \Piwik\Plugin {
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
        $translationKeys[] = 'Seven_ApiKey';
    }
}
