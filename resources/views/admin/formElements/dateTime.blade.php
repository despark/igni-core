<?php
/** @var \Despark\Cms\Fields\DateTime $field */
$fieldName = $field->getFieldName();
$elementName = $field->getElementName();
?>
<div class="form-group datetimepicker{{ $errors->has($fieldName) ? 'has-error' : '' }}">
    {!! Form::label($elementName, $field->getLabel()) !!}
    {!! Form::text($elementName, $field->getValue(), $field->getAttributes() ) !!}
    <div class="text-red">
        {{ join($errors->get($fieldName), '<br />') }}
    </div>
</div>
