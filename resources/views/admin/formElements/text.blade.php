<?php
/** @var \Despark\Cms\Fields\Select $this */
$fieldName = $field->getFieldName();
?>
<div class="form-group {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    {!! Form::label($fieldName, $field->getLabel()) !!}
    {!! Form::text($fieldName, $field->getValue(), $field->getAttributes()) !!}
    <div class="text-red">
        {{ join($errors->get($fieldName), '<br />') }}
    </div>
</div>
