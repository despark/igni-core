{{-- Image --}}
<div class="form-group {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    {!! Form::label($elementName, $field->getLabel()) !!}
    @if($field->getModel()->hasImages($fieldName))
        <div class="form-group">
            @foreach($field->getModel()->getImages($fieldName) as $image)
                <div class="image-row">
                    {!! Html::image($image->getOriginalImagePath('admin'), $image->alt, ['title' => $image->title]) !!}
                </div>
                {!! $field->getModel()->getImageMetaFieldsHtml($fieldName, $image) !!}
            @endforeach
        </div>
        <div class="form-group">
            <label for="{{ $elementName.'_delete' }}">
                {!! Form::checkbox($elementName.'_delete',1,null,['id' => $elementName.'_delete']) !!}
                Delete
            </label>
        </div>
    @endif

    {!! Form::file($elementName,  [
        'id' => $elementName,
        'class' => "form-control",
        'placeholder' => $field->getLabel(),
    ] ) !!}

    @if(isset($options['help']))
        <div class="help-text">{{ $options['help']}}</div>
    @elseif($dimensions = $field->getModel()->getMinDimensions($fieldName, true))
        <div class="help-text">{{ trans('ignicms::admin.images.min_dimensions' , ['dimensions' => $dimensions]) }}</div>
    @endif
    <div class="text-red">
        {{ join($errors->get($fieldName), '<br />') }}
    </div>

</div>
