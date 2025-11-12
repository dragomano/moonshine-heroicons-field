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
	    $paths = [
		    [
			    'from' => __DIR__ . '/../../config/heroicons-field.php',
			    'to' => config_path('heroicons-field.php'),
			    'groups' => 'heroicons-field'
		    ],
		    [
			    'from' => __DIR__ . '/../../public',
			    'to' => public_path('vendor/moonshine-heroicons-field'),
			    'groups' => ['moonshine-heroicons-field', 'laravel-assets']
		    ],
		    [
			    'from' => base_path() . '/vendor/blade-ui-kit/blade-heroicons/resources/svg',
			    'to' => public_path('vendor/blade-heroicons'),
			    'groups' => ['blade-heroicons', 'laravel-assets']
		    ],
	    ];

	    if ($this->app->runningInConsole()) {
		    foreach ($paths as $path) {
			    $this->publishes([$path['from'] => $path['to']], $path['groups']);
		    }
	    }
    }
}
