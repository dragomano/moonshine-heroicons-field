<?php declare(strict_types=1);

/**
 * IconServiceProvider.php
 *
 * @package bugo/moonshine-heroicons-field
 * @link https://github.com/dragomano/moonshine-heroicons-field
 * @author Bugo <bugo@dragomano.ru>
 * @copyright 2024 Bugo
 * @license https://opensource.org/licenses/MIT MIT
 *
 * @version 0.2
 */

namespace Bugo\MoonShineHeroicons\Providers;

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
        }
    }
}
