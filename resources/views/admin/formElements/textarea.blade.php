<div class="form-group {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    {!! Form::label($elementName, $field->getLabel()) !!}
    {!! Form::textarea($elementName, $field->getValue(), $field->getAttributes()) !!}
    <div class="text-red">
        {{ join($errors->get($fieldName), '<br />') }}
    </div>
</div>
