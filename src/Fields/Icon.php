<?php declare(strict_types=1);

namespace Bugo\MoonShine\Heroicons\Fields;

use Closure;
use Illuminate\Support\Facades\Cache;
use MoonShine\Fields\Select;
use MoonShine\Fields\Preview;

class Icon extends Select
{
    protected string $currentStyle;

    protected array $assets = [
        'vendor/moonshine-heroicons-field/css/app.css',
    ];

    public function __construct(Closure|string|null $label = null, ?string $column = null, ?Closure $formatted = null)
    {
        parent::__construct($label, $column, $formatted);

        $this->currentStyle = $this->getStyle();
        $this->options = $this->getCustomOptions();
        $this->optionProperties = $this->getCustomOptionProperties();
    }

    public function options(Closure|array $data): static
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
            'm', 'mini'    => 'm',
            'o', 'outline' => 'o',
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
            static fn($icon) => svg("heroicon-$this->currentStyle-$icon", 'h-6 w-6')->toHtml(),
            $icons
        );

        return (string) Preview::make(formatted: static fn() => implode('', $result))
            ->setAttribute('class', 'flex items-center');
    }

    private function getCustomOptions(): array
    {
        return Cache::rememberForever("heroicons_{$this->currentStyle}_field_options", function () {
            $items = glob(public_path("vendor/blade-heroicons/$this->currentStyle-*.svg"));
            $items = array_map(static fn($item) => substr(basename($item, '.svg'), 2), $items);

            return array_combine($items, $items);
        });
    }

    private function getCustomOptionProperties(): array
    {
        return Cache::rememberForever("heroicons_{$this->currentStyle}_field_option_properties", function () {
            $link = asset("vendor/blade-heroicons/$this->currentStyle-%s.svg");

            return array_map(static fn($item) => ['image' => sprintf($link, $item)], $this->getCustomOptions());
        });
    }
}
