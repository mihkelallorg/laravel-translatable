<?php

namespace Mihkullorg\Tests\Translatable;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, MockeryPHPUnitIntegration;
}
