<?php

namespace Mihkullorg\Translatable\Contracts;

interface TranslatorInterface {

    public function detectLanguage($text);

    public function translate($text, $language);

}