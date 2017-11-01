{{-- Image --}}
<div class="form-group {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    {!! Form::label($elementName, $field->getLabel()) !!}
    @if($field->getModel()->hasImages($fieldName))
        <div class="form-group">
            @foreach($field->getModel()->getImages($fieldName) as $image)
                <div class="image-row">
                    @if (pathinfo($image->getOriginalImagePath('original'), PATHINFO_EXTENSION) === 'svg')
                        {!! Html::image($image->getOriginalImagePath('original'), $image->alt, ['title' => $image->title]) !!}
                    @else
                        @php 
                            $imgTag = Html::image($image->getOriginalImagePath('admin'), $image->alt, ['title' => $image->title]);
                        @endphp
                        {!! ($field->getOptions('previewLink') === true) ? '<a href="'.asset($image->getSourceImagePath()).'" target="_blank">'.$imgTag.'</a>' : $imgTag !!}
                    @endif
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

    @if($field->getHelp())
        <div class="help-text">{{ $field->getHelp() }}</div>
    @elseif($dimensions = $field->getModel()->getMinDimensions($fieldName, true))
        <div class="help-text">{{ trans('ignicms::admin.images.min_dimensions' , ['dimensions' => $dimensions]) }}</div>
    @endif
    <div class="text-red">
        {{ join($errors->get($fieldName), '<br />') }}
    </div>

</div>
