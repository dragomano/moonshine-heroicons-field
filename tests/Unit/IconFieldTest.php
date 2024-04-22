<?php declare(strict_types=1);

use Bugo\MoonShine\Heroicons\Fields\Icon;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    $this->field = Icon::make('Icon');

    $this->item = new class () extends Model {
        public string $icon = 'moon';
    };

    fillFromModel($this->field, $this->item);
});

describe('updated methods', function () {
    it('overrides option and optionProperties', function (): void {
        expect(
            $this->field->options(['1' => '2'])
                ->optionProperties(['1' => ['image' => 'https://www.svgrepo.com/show/397179/panda.svg']])
                ->getOptionProperties('1')
        )->toBe([]);
    });
});

describe('new methods', function () {
    it('adds style method', function(): void {
        expect($this->field->style('s')->toValue())
            ->toBe($this->field->style('solid')->toValue())
            ->and($this->field->style('o')->toValue())
            ->toBe($this->field->style('outline')->toValue())
            ->and($this->field->style('m')->toValue())
            ->toBe($this->field->style('mini')->toValue())
            ->and($this->field->style('c')->toValue())
            ->toBe($this->field->style('micro')->toValue());
    });
});
