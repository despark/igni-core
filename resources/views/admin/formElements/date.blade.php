<div class="form-group {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    {!! Form::label($elementName, $field->getLabel()) !!}
    <div class="datepicker input-group">
        {!! Form::text($elementName, $field->getValue(), $field->getAttributes()) !!}
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
