# MoonShine Heroicons Field

![PHP](https://img.shields.io/badge/PHP-^8.1-blue.svg?style=flat)
[![Coverage Status](https://coveralls.io/repos/github/dragomano/moonshine-heroicons-field/badge.svg?branch=main)](https://coveralls.io/github/dragomano/moonshine-heroicons-field?branch=main)

Convenient Heroicons selection field for [MoonShine](https://github.com/moonshine-software/moonshine)

## Installation

```bash
composer require bugo/moonshine-heroicons-field
```

## Configuration

You can specify desired default style for icons:

`.env`:

```ini
HEROICONS_STYLE=outline
```

`config/heroicons-field.php`:

```bash
php artisan vendor:publish --tag=heroicons-field
```

```php
/*
 * Possible values:
 * 's', 'solid' - Solid 24x24, Solid fill
 * 'o', 'outline' - Outline 24x24, 1.5px stroke
 * 'm', 'mini' - Mini 20x20, Solid fill
 * 'c', 'micro' - Micro 16x16, Solid fill
 */
return [
    'style' => env('HEROICONS_STYLE', 'solid'),
];
```

## Usage

You can use `Icon` field in your resources:

```php
<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Bugo\MoonShine\Heroicons\Fields\Icon;
use MoonShine\Resources\ModelResource;

/**
 * @extends ModelResource<Custom>
 */
class CustomResource extends ModelResource
{
    public function fields(): array
    {
        return [
            Icon::make('Icon')
                ->searchable()
                ->style('mini'),
        ];
    }
}
```

All use cases of [Blade Heroicons](https://github.com/blade-ui-kit/blade-heroicons#usage) are also available for you.

## Caching

When using icons in Blade templates, be sure to enable [Caching](https://github.com/blade-ui-kit/blade-icons?tab=readme-ov-file#caching).

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.
