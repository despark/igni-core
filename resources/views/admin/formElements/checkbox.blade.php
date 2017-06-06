<div class="form-group">
    <div class="checkbox">
        <label for="{{ $elementName }}">
            {!! Form::hidden($elementName, 0) !!}
            {!! Form::checkbox($elementName, 1, $field->getValue(), $field->getAttributes()) !!}
            {{ $field->getLabel() }}
        </label>
    </div>
</div>
