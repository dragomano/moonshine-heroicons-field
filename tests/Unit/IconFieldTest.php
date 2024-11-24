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
