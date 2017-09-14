{{-- TODO action verb --}}
{!!  Form::open([
    'url' => $form->getAction(),
    'method' => $form->getMethod(),
    'role' => $form->getRole(),
    'enctype'=> $form->getEnctype() ?? 'multipart/form-data', ]
) !!}

{!! $form->renderFields() !!}

<button type="submit" class="btn btn-primary">Save</button>

<?php $resourceConfig = $controller->getResourceConfig() ?>
@if(isset($resourceConfig['parentModel']) AND request()->has($resourceConfig['parentModel']['foreignKey']))
   <a href="{{ route($resourceConfig['id'].'.index').'?'.$resourceConfig['parentModel']['foreignKey'].'='.request()->query($resourceConfig['parentModel']['foreignKey']) }}" class="back-to-filtered-listing">Back to listing</a> 
@endif
{{-- {!! $record->adminPreviewButton() !!} --}}

{!! Form::close() !!}