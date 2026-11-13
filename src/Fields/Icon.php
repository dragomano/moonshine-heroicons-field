<?php

declare(strict_types=1);

namespace Bugo\MoonShine\Heroicons\Fields;

use Closure;
use Illuminate\Support\Facades\Cache;
use JsonException;
use MoonShine\AssetManager\Css;
use MoonShine\Support\DTOs\Select\OptionImage;
use MoonShine\Support\DTOs\Select\Options;
use MoonShine\Support\Enums\ObjectFit;
use MoonShine\UI\Fields\Preview;
use MoonShine\UI\Fields\Select;

class Icon extends Select
{
    protected string $currentStyle;

    public function __construct(Closure|string|null $label = null, string|null $column = null, Closure|null $formatted = null)
    {
        parent::__construct($label, $column, $formatted);

        $this->currentStyle = $this->getStyle();
        $this->options = $this->getCustomOptions();
        $this->optionProperties = $this->getCustomOptionProperties();
    }

    public function options(array|Closure|Options $data): static
    {
        return $this;
    }

    public function optionProperties(array|Closure $data): static
    {
        return $this;
    }

    public function useStyle(string $style): static
    {
        $this->currentStyle = $this->getShortStyle($style);

        $this->options = $this->getCustomOptions();
        $this->optionProperties = $this->getCustomOptionProperties();

        return $this;
    }

    protected function booted(): void
    {
        parent::booted();

        $this->getAssetManager()
            ->add(Css::make('vendor/moonshine-heroicons-field/css/app.css'));
    }

    protected function isPreviewMarkup(): bool
    {
        return true;
    }

    protected function getShortStyle(string $style): string
    {
        return match ($style) {
            'c', 'micro' => 'c',
            'm', 'mini' => 'm',
            'o', 'outline' => 'o',
            default => 's'
        };
    }

    protected function getStyle(): string
    {
        return $this->currentStyle ?? $this->getShortStyle(config('heroicons-field.style'));
    }

    /**
     * @throws JsonException
     */
    protected function resolvePreview(): string
    {
        $value = parent::resolvePreview();

        if ($value === '') {
            return '';
        }

        $icons = array_filter(explode(',', $value));
        $style = $this->currentStyle;

        $result = array_map(
            static fn (string $icon): string => svg("heroicon-$style-$icon", 'h-6 w-6')->toHtml(),
            $icons
        );

        return (string) Preview::make(formatted: static fn (): string => implode('', $result))
            ->setAttribute('class', 'flex items-center');
    }

    private function getCustomOptions(): array
    {
        return Cache::rememberForever("heroicons_{$this->currentStyle}_field_options", function (): array {
            $items = glob(public_path("vendor/blade-heroicons/$this->currentStyle-*.svg"));
            $items = array_map(static fn ($item): string => substr(basename($item, '.svg'), 2), $items);

            return array_combine($items, $items);
        });
    }

    private function getCustomOptionProperties(): array
    {
        return Cache::rememberForever("heroicons_{$this->currentStyle}_field_option_properties", function (): array {
            $link = asset("vendor/blade-heroicons/$this->currentStyle-%s.svg");

            return array_map(static fn ($item): array => [
                'image' => new OptionImage(
                    sprintf($link, $item),
                    6,
                    6,
                    ObjectFit::CONTAIN
                ),
            ], $this->getCustomOptions());
        });
    }
}
