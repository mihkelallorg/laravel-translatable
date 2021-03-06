<?php

use Faker\Generator as Faker;
use Mihkullorg\Tests\Translatable\Models\TestModel;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(TestModel::class, function (Faker $faker) {
    return [
        'body' => $faker->text(200),
    ];
});
