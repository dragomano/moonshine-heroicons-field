<?php

declare(strict_types=1);

use Bugo\MoonShine\Heroicons\Fields\Icon;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Support\DTOs\Select\Option;
use MoonShine\Support\DTOs\Select\OptionProperty;
use MoonShine\Support\DTOs\Select\Options;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    $this->field = Icon::make('Icon');

    $this->item = new class extends Model
    {
        public string $icon = 'moon';
    };

    fillFromModel($this->field, $this->item);
});

test('icon field can be instantiated', function () {
    expect($this->field)
        ->toBeInstanceOf(Icon::class)
        ->and($this->field->getLabel())->toBe('Icon');
});

describe('override methods', function () {
    it('overrides option', function (): void {
        expect(
            $this->field->options(new Options([
                new Option(
                    'panda',
                    'panda',
                    properties: new OptionProperty('https://www.svgrepo.com/show/397179/panda.svg')
                ),
            ]))
                ->getValues()
                ->toArray()
        )->toBe([]);
    });

    it('overrides optionProperties', function (): void {
        expect(
            $this->field->optionProperties(fn () => [
                'panda' => ['image' => 'https://www.svgrepo.com/show/397179/panda.svg'],
            ])
                ->getValues()
                ->toArray()
        )->toBe([]);
    });
});

describe('new methods', function () {
    it('adds useStyle method', function (): void {
        expect($this->field->useStyle('s')->toValue())
            ->toBe($this->field->useStyle('solid')->toValue())
            ->and($this->field->useStyle('o')->toValue())
            ->toBe($this->field->useStyle('outline')->toValue())
            ->and($this->field->useStyle('m')->toValue())
            ->toBe($this->field->useStyle('mini')->toValue())
            ->and($this->field->useStyle('c')->toValue())
            ->toBe($this->field->useStyle('micro')->toValue());
    });
});

test('resolvePreview returns empty string for empty value', function () {
    expect(Icon::make('Icon')->preview())->toBe('');
});

describe('rendering', function () {
    beforeEach(function (): void {
        publishHeroicons('s', 'moon');
    });

    afterEach(function (): void {
        removePublishedHeroicons();
    });

    it('renders svg markup in preview without escaping', function (): void {
        $field = Icon::make('Icon');

        fillFromModel($field, $this->item);

        $preview = (string) $field->preview();

        expect($preview)
            ->toContain('<svg')
            ->not->toContain('&lt;svg');
    });

    it('adds image property to options', function (): void {
        $options = Icon::make('Icon')->getValues()->toArray();

        expect($options)->toHaveKey('moon')
            ->and($options['moon']['properties']['image']['src'])
            ->toContain('vendor/blade-heroicons/s-moon.svg')
            ->and($options['moon']['properties']['image']['objectFit'])->toBe('contain');
    });

    it('keeps markup in the escaped link name', function (): void {
        $field = Icon::make('Icon')
            ->link('#', fn (string $value): string => $value);

        fillFromModel($field, $this->item);

        $preview = (string) $field->preview();

        expect($preview)
            ->toContain('<svg')
            ->not->toContain('&lt;svg');
    });
});

describe('useStyle', function () {
    beforeEach(function (): void {
        publishHeroicons('s', 'moon');
        publishHeroicons('o', 'moon');
    });

    afterEach(function (): void {
        removePublishedHeroicons();
    });

    it('reloads options for the selected style', function (): void {
        $options = Icon::make('Icon')
            ->useStyle('outline')
            ->getValues()
            ->toArray();

        expect($options)->toHaveKey('moon')
            ->and($options['moon']['properties']['image']['src'])
            ->toContain('vendor/blade-heroicons/o-moon.svg');
    });

    it('renders preview with the selected style', function (): void {
        $field = Icon::make('Icon')
            ->useStyle('outline');

        fillFromModel($field, $this->item);

        expect((string) $field->preview())
            ->toContain('<svg')
            ->not->toContain('&lt;svg');
    });
});
