<?php

namespace Mihkullorg\Tests\Translatable;

use Hash;

trait CreatesApplication
{
    protected function getPackageProviders($app)
    {
        return ['Mihkullorg\Translatable\TranslatableServiceProvider'];
    }

    protected function getPackageAliases($app)
    {
        return [];
    }

    protected function getEnvironmentSetup($app)
    {
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'sqlite']);
        $this->artisan('migrate', ['--database' => 'sqlite', '--path' => '../../../../tests/migrations']);

        $this->loadLaravelMigrations(['--database' => 'sqlite']);
        $this->withFactories(__DIR__.'/factories');
    }

    /**
     * Creates the application.
     *
     * Needs to be implemented by subclasses.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = parent::createApplication();

        Hash::setRounds(4);

        return $app;
    }
}
