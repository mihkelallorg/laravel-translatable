# Laravel translatable

[![Latest Stable Version](https://poser.pugx.org/mihkullorg/laravel-translatable/version)](https://packagist.org/packages/mihkullorg/laravel-translatable)
[![Total Downloads](https://poser.pugx.org/mihkullorg/laravel-translatable/downloads)](https://packagist.org/packages/mihkullorg/laravel-translatable)
[![Build status](https://circleci.com/gh/mihkelallorg/laravel-translatable.png?style=shield)](https://circleci.com/gh/mihkelallorg/laravel-translatable)
[![StyleCI](https://github.styleci.io/repos/165062826/shield?branch=master)](https://github.styleci.io/repos/165062826)
[![License](https://poser.pugx.org/mihkullorg/laravel-translatable/license)](https://packagist.org/packages/mihkullorg/laravel-translatable) 


This is a Laravel package for (auto)translatable models. It provides functionality of multilingual models with an option of 
using external services for automatic translations. [Google Cloud Translate](https://cloud.google.com/translate/docs/) is already integrated into the package 
and adding another service is made pretty easy.

If you're not interested in using any automatic translation services, and just looking to make your models multilingual I 
suggest using [dismav/laravel-translatable package](https://packagist.org/packages/dimsav/laravel-translatable) as it 
has a lot more history and community behind it is way way larger. 

### Index

- Info
- Quick start
- FAQ
- Contribution

## Info

The package is create for Laravel 5.6 and 5.7.

## Quick start

#### Step 1: Install package

Add the package in your composer.json by executing the command.

```composer require mihkullorg/laravel-translatable```


Next, add the service provider to `config/app.php`

```Mihkullorg\Translatable\TranslatableServiceProvider::class```


#### Step 2: Set up the environment and migration. 

Publish the migration file.

```php artisan vendor:publish --tag=translatable```

Set up your [Google Cloud Translate/GCT](https://cloud.google.com/translate/docs/) service account and select your preferred way of 
authentication from [Googe Cloud authenticiation docs](https://cloud.google.com/docs/authentication/). As Google supports authenticating
through environment variables and it being really comfortable, this package is using it too. I prefer authenticating by setting 
`GOOGLE_APPLICATION_CREDENTIALS` variable to the path of the provided service account private key file.

#### Step 3: Add Translatable trait to model

```php
use Mihkullorg\Translatable\Traits\Translatable;

class Post extends Model 
{
    use Translatable;
    
    public $fillable = ['body'];
}
```

The trait adds necessary translation relationship methods
```php
$post->translations();
$post->translation($field, $language, $createIfNotFresh = false);
```

Translation is "fresh" when the `updated_at` of the `Translation` model is older than the `update_at` of the `Translatable` model.
```php
$post->freshTranslation($field, $language);
```

To create a translation manually, you can call method `translate($field, $language, $value = null)`
```php
$post->translate('body', 'en', 'New translated body');
```

To use the GCT service, do not specify the value and the translation is retrieved and model created automatically.
```php
$translation = $post->translate('body', 'en');
```

`translation` method acts similarly and if `$createIfNotFresh` is set `true`, it tries to retrieve a fresh translation from the database
and if none exists, uses the GCT service.


## FAQ

- What about Laravel <5.6?

 I'm honestly not sure whether it would work with older version than 5.6 
but as of creating this package, only 5.6 and 5.7 are officially supported. 
If anyone is willing to test it on earlier versions and make sure it works, create an issue and let's discuss it.

## Contribution

Feel free to create issues and make PRs to make this package better. I hope you do. :) 