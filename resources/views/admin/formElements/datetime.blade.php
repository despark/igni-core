<?php
/** @var \Despark\Cms\Fields\DateTime $field */
$fieldName = $field->getFieldName();
$elementName = $field->getElementName();
?>
<div class="form-group {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    {!! Form::label($elementName, $field->getLabel()) !!}
    <div class="datetimepicker input-group">
        {!! Form::text($elementName, $field->getValue(), $field->getAttributes() ) !!}
    </div>
    @if($field->getHelp())
        <p class="help-text">
            {{$field->getHelp()}}
        </p>
    @endif
    <div class="text-red">
        {{ join($errors->get($fieldName), '<br />') }}
    </div>
</div>
