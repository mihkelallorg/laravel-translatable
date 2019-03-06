<?php

namespace Mihkullorg\Translatable\Traits;

use Illuminate\Support\Facades\App;
use Mihkullorg\Translatable\Facades\Translator;
use Mihkullorg\Translatable\Models\Translation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Translatable
{
    public function translate($field, $language, $translation = null)
    {
        if (! $translation) {
            $translation = Translator::translate($this->$field, $language);
        }

        return $this->createTranslation($field, $language, $translation);
    }

    /**
     * @param string $field Translatable field name
     * @param string $language Language code
     * @param bool $createIfNotFresh
     * @return Translation
     */
    public function translation($field, $language, $createIfNotFresh = false)
    {
        $translation = $this->translations()->field($field)->language($language)->first();

        if (! $createIfNotFresh || $translation->isFresherThan($this->created_at)) {
            return $translation;
        }

        return $this->translate($field, $language);
    }

    /**
     * @return MorphMany
     */
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    /**
     * @param string $field Translatable field name
     * @param string $language Language code
     * @return int
     */
    public function deleteTranslation($field, $language)
    {
        return $this->translations()->field($field)->language($language)->delete();
    }

    /**
     * @param string $field Translatable field name
     * @param string $language Language code
     * @param string $translation
     * @return Translation
     */
    public function createTranslation($field, $language, $translation)
    {
        return $this->translations()->updateOrCreate([
            'field' => $field,
            'language' => $language,
        ], [
            'value' => $translation,
        ]);
    }

    /**
     * [
     *  [
     *      'language' => 'en',
     *      'body' => 'English body translations',
     *      'title' => '...',
     *  ],
     *  [
     *      'language' => 'ru',
     *      'body' => 'Russian body translations',
     *      'title' => '...',
     * ].
     *
     * @return array[]
     */
    public function getTranslationsAsArray()
    {
        return $this->translations->groupBy('language')->map(function ($language) {
            return $language->groupBy('field')->map(function ($field) {
                return $field->first()->value;
            });
        })->each(function ($values, $language) {
            $values->put('language', $language);
        })->values()->toArray();
    }

    /**
     * Get the translated field value
     *
     * @param $field
     * @param null $language Defaults to the App locale
     * @param null $default The return value if the translation does not exist
     * @return string
     */
    public function getTranslated($field, $language = null, $default = null)
    {
        $language = $language ?: App::getLocale();

        return object_get($this->translation($field, $language), 'value', $default);
    }
}
