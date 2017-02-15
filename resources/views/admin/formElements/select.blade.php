<div class="form-group {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    {!! Form::label($fieldName, $field->getLabel()) !!}
    {!! Form::select($fieldName, $field->getSelectOptions(), $field->getValue(), $field->getAttributes()) !!}
    @if($field->getHelp())
        <p class="help-text">
            {{$field->getHelp()}}
        </p>
    @endif
    <div class="text-red">
        {{ join($errors->get($fieldName), '<br />') }}
    </div>
</div>
