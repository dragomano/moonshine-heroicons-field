<?php declare(strict_types=1);

/**
 * Icon.php
 *
 * @package bugo/moonshine-heroicons-field
 * @link https://github.com/dragomano/moonshine-heroicons-field
 * @author Bugo <bugo@dragomano.ru>
 * @copyright 2024 Bugo
 * @license https://opensource.org/licenses/MIT MIT
 *
 * @version 0.1
 */

namespace Bugo\MoonShineHeroicons\Fields;

use Closure;
use Illuminate\Support\Facades\Cache;
use MoonShine\Components\Icon as IconComponent;
use MoonShine\Fields\Select;

class Icon extends Select
{
    public function __construct(Closure|string|null $label = null, ?string $column = null, ?Closure $formatted = null)
    {
        parent::__construct($label, $column, $formatted);

        $this->options = $this->getCustomOptions();
        $this->optionProperties = fn() => $this->getCustomOptionProperties();
    }

    public function options(array|Closure $data): static
    {
        return $this;
    }

    public function optionProperties(array|Closure $data): static
    {
        return $this;
    }

    public function getCustomOptions(): array
    {
        return Cache::rememberForever('heroicons-field-options', function () {
            $items = glob(base_path('vendor/moonshine/moonshine/resources/views/ui/icons/heroicons/*.blade.php'));
            $items = array_map(fn($item) => basename($item, '.blade.php'), $items);

            return array_combine($items, $items);
        });
    }

    public function getCustomOptionProperties(): array
    {
        return Cache::rememberForever('heroicons-field-option-properties', function () {
            $style = config('heroicons-field.style');
            $link = "https://raw.githubusercontent.com/tailwindlabs/heroicons/master/optimized/24/$style/%s.svg";

            return array_map(fn($item) => ['image' => sprintf($link, $item)], $this->getCustomOptions());
        });
    }

    /**
     * @codeCoverageIgnore
     */
    protected function resolvePreview(): string
    {
        $value = parent::resolvePreview();

        if (empty($value)) {
            return '';
        }

        $style = str_replace('solid', '', (string) config('heroicons-field.style'));

        $icons = array_filter(explode(',', $value));

        $result = array_map(fn($icon) => IconComponent::make("heroicons.$style.$icon")->render(), $icons);

        return '<div class="flex items-center">' . implode('', $result) . '</div>';
    }
}
