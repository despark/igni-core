<div class="form-group {{ $errors->has($field->getOptions('validateName')) ? 'has-error' : '' }}">
    <label for="{{ $fieldName }}">{{ $field->getLabel() }}</label>
    <select 
        id="{{ $fieldName }}" 
        name="{{ $fieldName }}" 
        class="form-control select2 {{ $field->getOptions('additionalClass') }}" 
        multiple 
        style="width: 100%">
        @foreach($field->getSelectOptions() as $key => $value)
            <option value="{{ $key }}" 
                {{ in_array($key, $field->getRelationMethod()->pluck($field->getOptions('selectedKey'))->all()) ? 'selected' : '' }}>
                {{ $value }}
            </option>
        @endforeach
    </select>
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
