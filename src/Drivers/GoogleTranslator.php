<?php

namespace Mihkullorg\Translatable\Drivers;

use Google\Cloud\Translate\TranslateClient;
use Mihkullorg\Translatable\Contracts\TranslatorInterface;

class GoogleTranslator implements TranslatorInterface
{
    private $client;

    public function __construct()
    {
        $this->client = new TranslateClient();
    }

    public function detectLanguage($text)
    {
        $response = $this->client->detectLanguage($text);

        return data_get($response, 'languageCode');
    }

    public function translate($text, $language)
    {
        $response = $this->client->translate($text, ['target' => $language]);

        return data_get($response, 'text');
    }
}
