<?php
/** @var \Despark\Cms\Fields\DateTime $field */
$fieldName = $field->getFieldName();
$elementName = $field->getElementName();
?>
<div class="form-group {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    {!! Form::label($elementName, $field->getLabel()) !!}
    <div class="datepicker input-group">
        {!! Form::text($elementName, $field->getValue(), $field->getAttributes()) !!}
    </div>
    <div class="text-red">
        {{ join($errors->get($fieldName), '<br />') }}
    </div>
</div>