<?php

use Illuminate\Database\Eloquent\Model;
use MoonShine\Fields\Field;

function fillFromModel(Field $field, Model $model): void
{
    $field->resolveFill($model->toArray(), $model);
}
