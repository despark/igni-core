<div class="form-group {{ $errors->has($field->getOptions('validateName')) ? 'has-error' : '' }}">
    {!! Form::label($fieldName, $field->getLabel()) !!}
    {!! Form::select($fieldName, $field->getSelectOptions(), $field->getRelationMethod()->pluck($field->getOptions('selectedKey'))->all(), [
            'class' => 'form-control '.('select2').' '.$field->getOptions('additionalClass'),
            'multiple' => 'multiple',
            'style' => 'width: 100%',
        ]) !!}
    <div class="text-red">
        {{ join($errors->get($field->getOptions('validateName')), '<br />') }}
    </div>
</div>

@push('additionalScripts')
    <script type="text/javascript">
        $(".select2").select2({
            placeholder: 'Select {{ $field->getLabel() }}'
        });
    </script>
@endpush
