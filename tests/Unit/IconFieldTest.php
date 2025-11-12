<?php declare(strict_types=1);

use Bugo\MoonShine\Heroicons\Fields\Icon;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Support\DTOs\Select\Option;
use MoonShine\Support\DTOs\Select\OptionProperty;
use MoonShine\Support\DTOs\Select\Options;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    $this->field = Icon::make('Icon');

    $this->item = new class () extends Model {
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
                )
            ]))
                ->getValues()
                ->toArray()
        )->toBe([]);
    });

    it('overrides optionProperties', function (): void {
        expect(
            $this->field->optionProperties(fn() => [
                'panda' => ['image' => 'https://www.svgrepo.com/show/397179/panda.svg']
            ])
                ->getValues()
                ->toArray()
        )->toBe([]);
    });
});

describe('new methods', function () {
    it('adds useStyle method', function(): void {
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
