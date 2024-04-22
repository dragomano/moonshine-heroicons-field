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
 * @version 0.2.1
 */

namespace Bugo\MoonShineHeroicons\Fields;

use Closure;
use Illuminate\Support\Facades\Cache;
use MoonShine\Fields\Select;
use MoonShine\Fields\Preview;

class Icon extends Select
{
    protected string $style;

    protected array $assets = [
        'vendor/moonshine-heroicons-field/css/app.css',
    ];

    public function __construct(Closure|string|null $label = null, ?string $column = null, ?Closure $formatted = null)
    {
        parent::__construct($label, $column, $formatted);

        $this->style = $this->getStyle();
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
        return Cache::rememberForever("heroicons-$this->style-field-options", function () {
            $items = glob(public_path("vendor/blade-heroicons/$this->style-*.svg"));
            $items = array_map(fn($item) => substr(basename($item, '.svg'), 2), $items);

            return array_combine($items, $items);
        });
    }

    public function getCustomOptionProperties(): array
    {
        return Cache::rememberForever("heroicons-$this->style-field-option-properties", function () {
            $link = asset("vendor/blade-heroicons/$this->style-%s.svg");

            return array_map(fn($item) => ['image' => sprintf($link, $item)], $this->getCustomOptions());
        });
    }

    public function style(string $style): static
    {
        $this->style = $this->getShortStyle($style);

        return $this;
    }

    protected function getShortStyle(string $style): string
    {
        return match ($style) {
            'o', 'outline' => 'o',
            'm', 'mini'    => 'm',
            'c', 'micro'   => 'c',
            default        => 's'
        };
    }

    protected function getStyle(): string
    {
        return $this->style ?? $this->getShortStyle(config('heroicons-field.style'));
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    protected function resolvePreview(): string
    {
        $value = parent::resolvePreview();

        if (empty($value)) {
            return '';
        }

        $icons = array_filter(explode(',', $value));

        $result = array_map(fn($icon) => svg("heroicon-$this->style-$icon", 'h-6 w-6')->toHtml(), $icons);

        return (string) Preview::make(formatted: static fn() => implode('', $result))
            ->setAttribute('class', 'flex items-center');
    }
}
