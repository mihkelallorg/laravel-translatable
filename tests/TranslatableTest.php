<?php

namespace Mihkullorg\Tests\Translatable;

use Carbon\Carbon;
use Mihkullorg\Tests\Translatable\Models\TestModel;
use Mihkullorg\Translatable\Facades\Translator;

class TranslatableTest extends TestCase
{
    public function test_translating_model_field_manually()
    {
        /** @var TestModel $model */
        $model = factory(TestModel::class)->create();

        $field = 'body';
        $language = 'en';
        $value = 'Body in English';
        $model->translate($field, $language, $value);

        $this->assertTranslationExists($model, $field, $language, $value);
    }

    public function test_translating_model_field_with_translator()
    {
        /** @var TestModel $model */
        $model = factory(TestModel::class)->create();

        $field = 'body';
        $language = 'en';
        $translatedField = 'Translated body';

        Translator::shouldReceive('translate')->with($model->body, $language)->andReturn($translatedField);
        $model->translate($field, $language);

        $this->assertTranslationExists($model, $field, $language, $translatedField);
    }

    /**
     * Old translation should be deleted.
     * New translation should be created with Translator.
     */
    public function test_translating_an_updated_model()
    {
        /** @var TestModel $model */
        $model = factory(TestModel::class)->create();

        $field = 'body';
        $language = 'en';
        $translatedFieldValue = 'New translation';
        $oldTranslatedFieldValue = 'Old translation';

        $this->createTranslationInThePast($model, $field, $language, $oldTranslatedFieldValue);

        Translator::shouldReceive('translate')->with($model->body, $language)->andReturn($translatedFieldValue);

        $model->translation($field, $language, true);

        $this->assertTranslationExists($model, $field, $language, $translatedFieldValue);
        $this->assertTranslationMissing($model, $field, $language, $oldTranslatedFieldValue);
    }

    public function test_retrieve_translations_as_array()
    {
        /** @var TestModel $model */
        $model = factory(TestModel::class)->create();

        $field = 'body';
        $englishTranslation = 'English translation';
        $russianTranslation = 'Russian translation';
        $model->createTranslation($field, 'en', $englishTranslation);
        $model->createTranslation($field, 'ru', $russianTranslation);

        $translations = $model->getTranslationsAsArray();

        $this->assertCount(2, $translations);
        $this->assertTrue(in_array([
            'language' => 'en',
            'body' => $englishTranslation,
        ], $translations));

        $this->assertTrue(in_array([
            'language' => 'ru',
            'body' => $russianTranslation,
        ], $translations));
    }

    /**
     * @param TestModel $model
     * @param $field
     * @param $language
     * @param $value
     */
    private function assertTranslationExists(TestModel $model, $field, $language, $value)
    {
        $this->assertDatabaseHas('mt_translations', [
            'translatable_type' => $model->getMorphClass(),
            'translatable_id' => $model->id,
            'field' => $field,
            'language' => $language,
            'value' => $value,
        ]);
    }

    /**
     * @param TestModel $model
     * @param $field
     * @param $language
     * @param $value
     */
    private function assertTranslationMissing(TestModel $model, $field, $language, $value)
    {
        $this->assertDatabaseMissing('mt_translations', [
            'translatable_type' => $model->getMorphClass(),
            'translatable_id' => $model->id,
            'field' => $field,
            'language' => $language,
            'value' => $value,
        ]);
    }

    private function createTranslationInThePast(TestModel $model, $field, $language, $value)
    {
        $translation = $model->translations()->make(compact('field', 'language', 'value'));
        $translation->updated_at = Carbon::now()->subDay();
        $translation->save(['timestamps' => false]);

        return $translation;
    }
}
