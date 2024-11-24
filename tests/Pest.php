<?php

use Illuminate\Database\Eloquent\Model;
use MoonShine\UI\Fields\Field;

function fillFromModel(Field $field, Model $model): void
{
    $field->fillData($model);
}
