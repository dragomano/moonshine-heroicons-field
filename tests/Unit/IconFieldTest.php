<?php declare(strict_types=1);

use Bugo\MoonShineHeroicons\Fields\Icon;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

uses(TestCase::class)->group('fields');

beforeEach(function (): void {
    $this->field = Icon::make('Icon');

    $this->selectOptions = $this->field->getCustomOptions();
    $this->selectOptionProperties = $this->field->getCustomOptionProperties();

    $this->fieldMultiple = Icon::make('Icon multiple')
        ->multiple();

    $this->item = new class () extends Model {
        public string $icon = 'archive-box-arrow-down';
        public array $icon_multiple = ['archive-box-arrow-down', 'archive-box-arrow-up'];

        protected $casts = [
            'icon_multiple' => 'json',
        ];
    };

    fillFromModel($this->field, $this->item);
    fillFromModel($this->fieldMultiple, $this->item);
});

describe('basic methods', function () {
    it('type', function (): void {
        expect($this->field->type())
            ->toBeEmpty();
    });

    it('view', function (): void {
        expect($this->field->getView())
            ->toBe('moonshine::fields.select');
    });

    it('preview', function (): void {
        expect($this->field->preview())
            ->toBe('')
            ->and((string) $this->fieldMultiple)
            ->toBe(
                view('moonshine::fields.select', $this->fieldMultiple->toArray())->render()
            );
    });

    it('change preview', function () {
        expect($this->field->changePreview(static fn () => 'changed'))
            ->preview()
            ->toBe('changed');
    });

    it('default value', function () {
        $field = Icon::make('Icon')->default('archive-box-arrow-down');

        expect($field->toValue())
            ->toBe('archive-box-arrow-down');
    });

    it('multiple', function (): void {
        expect($this->field->isMultiple())
            ->toBeFalse()
            ->and($this->fieldMultiple->isMultiple())
            ->toBeTrue();
    });

    it('searchable', function (): void {
        expect($this->fieldMultiple)
            ->isSearchable()
            ->toBeFalse()
            ->and($this->fieldMultiple->searchable())
            ->isSearchable()
            ->toBeTrue();
    });

    it('options', function (): void {
        expect($this->fieldMultiple)
            ->values()
            ->toBe($this->selectOptions);
    });

    it('option properties', function (): void {
        expect(
            $this->field->options(['1' => '2'])
                ->optionProperties(['1' => ['image' => 'https://some.site/1.svg']])
                ->getOptionProperties('1')
        )->toBe([]);
    });

    it('is selected correctly', function (): void {
        expect($this->fieldMultiple)
            ->isSelected('archive-box-arrow-down')
            ->toBeTrue();
    });

    it('is selected invalid', function (): void {
        expect($this->fieldMultiple)
            ->isSelected($this->item, 'archive-box-arrow-up')
            ->toBeFalse();
    });

    it('names single', function (): void {
        expect($this->field)
            ->name()
            ->toBe('icon')
            ->name('archive-box-arrow-down')
            ->toBe('icon');
    });

    it('names multiple', function (): void {
        expect($this->fieldMultiple)
            ->name()
            ->toBe('icon_multiple[]')
            ->name('archive-box-arrow-up')
            ->toBe('icon_multiple[archive-box-arrow-up]');
    });
});
