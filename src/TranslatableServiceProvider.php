<?php

namespace Mihkullorg\Translatable;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class TranslatableServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->publishConfig();
        $this->app->singleton('mt-translator', function ($app) {
            return new TranslatorManager($app);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'translatable');
    }

    private function publishConfig()
    {
        $config = __DIR__ . '/../config/config.php';

        $this->publishes([
           $config => config_path('translatable.php'),
        ]);
    }
}
