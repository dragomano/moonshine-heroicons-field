<?php declare(strict_types=1);

namespace Tests;

use Bugo\MoonShine\Heroicons\Providers\IconServiceProvider;
use MoonShine\Commands\InstallCommand;
use MoonShine\Providers\MoonShineServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->performApplication();
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.debug', 'true');
        $app['config']->set('moonshine.cache', 'array');
    }

    protected function performApplication(): static
    {
        $this->artisan(InstallCommand::class, [
            '--without-user' => true,
            '--without-migrations' => true,
        ]);

        $this->artisan('vendor:publish --tag=heroicons-field');

        $this->artisan('config:clear');
        $this->artisan('view:clear');
        $this->artisan('cache:clear');

        return $this;
    }

    protected function getPackageProviders($app): array
    {
        return [
            MoonShineServiceProvider::class,
            IconServiceProvider::class,
        ];
    }
}
