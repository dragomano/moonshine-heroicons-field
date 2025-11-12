<?php declare(strict_types=1);

namespace Tests;

use Bugo\MoonShine\Heroicons\Providers\IconServiceProvider;
use MoonShine\Laravel\Providers\MoonShineServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.debug', 'true');
        $app['config']->set('moonshine.cache', 'array');
    }

    protected function getPackageProviders($app): array
    {
        return [
            MoonShineServiceProvider::class,
            IconServiceProvider::class,
        ];
    }
}
