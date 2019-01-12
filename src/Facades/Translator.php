<?php

namespace Mihkullorg\Translatable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string translate(string $field, string $language)
 */
class Translator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mt-translator';
    }
}
