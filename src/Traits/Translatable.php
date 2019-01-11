<?php

namespace Mihkullorg\Translatable;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Mihkullorg\Translatable\Facades\Translator;
use Mihkullorg\Translatable\Models\Translation;

trait Translatable
{
    public function translate($field, $language, $translation = null)
    {
        if (!$translation) {
            $translation = Translator::translate($this->$field, ['target' => $language]);
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
        $translation = $this->freshTranslation($field, $language);

        if ($translation || ! $createIfNotFresh) {
            return $translation;
        }

        $this->deleteTranslation($field, $language);

        return $this->translate($field, $language);
    }

    public function freshTranslations()
    {
        return $this->translations()->fresherThan($this->updated_at);
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
     * @return Translation
     */
    public function freshTranslation($field, $language)
    {
        return $this->freshTranslations()->field($field)->language($language)->first();
    }

    /**
     * @param string $field Translatable field name
     * @param string $language Language code
     * @return integer
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
        return $this->translations()->create([
            'field' => $field,
            'value' => $translation,
            'language' => $language,
        ]);
    }
}
