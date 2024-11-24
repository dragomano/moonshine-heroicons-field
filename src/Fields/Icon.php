<?php declare(strict_types=1);

namespace Bugo\MoonShine\Heroicons\Fields;

use Closure;
use Illuminate\Support\Facades\Cache;
use MoonShine\AssetManager\Css;
use MoonShine\Support\DTOs\Select\Options;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Preview;

class Icon extends Select
{
    protected string $currentStyle;

    public function __construct(Closure|string|null $label = null, ?string $column = null, ?Closure $formatted = null)
    {
        parent::__construct($label, $column, $formatted);

        $this->currentStyle = $this->getStyle();
        $this->options = $this->getCustomOptions();
        $this->optionProperties = fn() => $this->getCustomOptionProperties();
    }

    public function getAssets(): array
    {
        return [
            Css::make('vendor/moonshine-heroicons-field/css/app.css'),
        ];
    }

    public function options(Closure|Options|array $data): static
    {
        return $this;
    }

    public function optionProperties(Closure|array $data): static
    {
        return $this;
    }

    public function useStyle(string $style): static
    {
        $this->currentStyle = $this->getShortStyle($style);

        return $this;
    }

    protected function getShortStyle(string $style): string
    {
        return match ($style) {
            'c', 'micro'   => 'c',
            'o', 'outline' => 'o',
            'm', 'mini'    => 'm',
            default        => 's'
        };
    }

    protected function getStyle(): string
    {
        return $this->currentStyle ?? $this->getShortStyle(config('heroicons-field.style'));
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    protected function resolvePreview(): string
    {
        $value = parent::resolvePreview();

        if ($value === '') {
            return '';
        }

        $icons = array_filter(explode(',', $value));

        $result = array_map(
            fn($icon) => svg("heroicon-$this->currentStyle-$icon", 'h-6 w-6')->toHtml(),
            $icons
        );

        return (string) Preview::make(formatted: static fn() => implode('', $result))
            ->setAttribute('class', 'flex items-center');
    }

    private function getCustomOptions(): array
    {
        return Cache::rememberForever("heroicons-$this->currentStyle-field-options", function () {
            $items = glob(public_path("vendor/blade-heroicons/$this->currentStyle-*.svg"));
            $items = array_map(fn($item) => substr(basename($item, '.svg'), 2), $items);

            return array_combine($items, $items);
        });
    }

    /**
     * @codeCoverageIgnore
     */
    private function getCustomOptionProperties(): array
    {
        return Cache::rememberForever("heroicons-$this->currentStyle-field-option-properties", function () {
            $link = asset("vendor/blade-heroicons/$this->currentStyle-%s.svg");

            return array_map(fn($item) => ['image' => sprintf($link, $item)], $this->getCustomOptions());
        });
    }
}
