<?php
/** @var \Despark\Cms\Fields\Select $field */
$fieldName = $field->getFieldName();
?>
<div class="form-group {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    {!! Form::label($fieldName, $field->getLabel()) !!}
    {!! Form::select($fieldName, $field->getSelectOptions(), $field->getValue(), $field->getAttributes()) !!}
    <div class="text-red">
        {{ join($errors->get($fieldName), '<br />') }}
    </div>
</div>