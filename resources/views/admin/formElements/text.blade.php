<div class="form-group {{ $errors->has($elementName) ? 'has-error' : '' }}">
    {!! Form::label($elementName, $field->getLabel()) !!}
    {!! Form::text($elementName, $field->getValue(), $field->getAttributes()) !!}
    @if($field->getHelp())
        <p class="help-text">
            {{$field->getHelp()}}
        </p>
    @endif
    <div class="text-red">
        {{ join($errors->get($elementName), '<br />') }}
    </div>
</div>
