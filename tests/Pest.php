<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use MoonShine\UI\Fields\Field;

function fillFromModel(Field $field, Model $model): void
{
    $field->fillData($model);
}

function publishHeroicons(string $style, string ...$names): void
{
    $directory = public_path('vendor/blade-heroicons');

    if (! is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    foreach ($names as $name) {
        file_put_contents(
            $directory . '/' . $style . '-' . $name . '.svg',
            '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M0 0h24v24H0z"/></svg>'
        );
    }

    Cache::forget("heroicons_{$style}_field_options");
    Cache::forget("heroicons_{$style}_field_option_properties");
}

function removePublishedHeroicons(): void
{
    $directory = public_path('vendor/blade-heroicons');

    if (! is_dir($directory)) {
        return;
    }

    foreach (glob($directory . '/*.svg') ?: [] as $file) {
        unlink($file);
    }
}
