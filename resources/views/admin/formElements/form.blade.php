{{-- TODO action verb --}}
{!!  Form::open([
    'url' => $form->getAction(),
    'method' => $form->getMethod(),
    'role' => $form->getRole(),
    'enctype'=> $form->getEnctype() ?? 'multipart/form-data', ]
) !!}

{!! $form->renderFields() !!}

<button type="submit" class="btn btn-primary">Save</button>
{{-- {!! $record->adminPreviewButton() !!} --}}

{!! Form::close() !!}