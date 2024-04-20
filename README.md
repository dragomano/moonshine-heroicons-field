# MoonShine Heroicons Field

![PHP](https://img.shields.io/badge/PHP-^8.1-blue.svg?style=flat)
[![Coverage Status](https://coveralls.io/repos/github/dragomano/moonshine-heroicons-field/badge.svg?branch=main)](https://coveralls.io/github/dragomano/moonshine-heroicons-field?branch=main)

Convenient Heroicons selection field for MoonShine

## Installation

```bash
composer require bugo/moonshine-heroicons-field
```

## Configuration

You can specify `outline` or `solid` (default) style for icons:

`.env`:

```ini
HEROICONS_STYLE=outline
```

`config/heroicons-field.php`:

```bash
php artisan vendor:publish --tag=heroicons-field
```

```php
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

use Bugo\MoonShineHeroicons\Fields\Icon;
use MoonShine\Resources\ModelResource;

/**
 * @extends ModelResource<Custom>
 */
class CustomResource extends ModelResource
{
    public function fields(): array
    {
        return [
            Icon::make('Icon')->searchable(),
        ];
    }
}
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.
