<?php declare(strict_types=1);

namespace Bugo\MoonShine\Heroicons\Providers;

use Illuminate\Support\ServiceProvider;

class IconServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/heroicons-field.php', 'heroicons-field');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/heroicons-field.php' => config_path('heroicons-field.php'),
            ], 'heroicons-field');

            $this->publishes([
                __DIR__ . '/../../public' => public_path('vendor/moonshine-heroicons-field'),
            ], ['moonshine-heroicons-field', 'laravel-assets']);

            $this->publishes([
                base_path() . '/vendor/blade-ui-kit/blade-heroicons/resources/svg'
                    => public_path('vendor/blade-heroicons'),
            ], ['blade-heroicons', 'laravel-assets']);
        }
    }
}
