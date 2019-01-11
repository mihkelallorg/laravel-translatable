<?php

namespace Mihkullorg\Translatable;

use Illuminate\Support\Manager;
use Mihkullorg\Translatable\Contracts\TranslatorInterface;
use Mihkullorg\Translatable\Drivers\GoogleTranslator;

class TranslatorManager extends Manager implements TranslatorInterface
{

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['translator.default'] ?? 'google';
    }

    public function detectLanguage($text)
    {
        return $this->driver()->detectLanguage($text);
    }

    public function translate($text, $language)
    {
        return $this->driver()->translate($text, $language);
    }

    public function createGoogleDriver()
    {
        return new GoogleTranslator();
    }
}
