{{-- TODO action verb --}}
{!!  Form::open([
    'url' => $form->getAction(),
    'method' => $form->getMethod(),
    'role' => $form->getRole(),
    'enctype'=> $form->getEnctype() ?? 'multipart/form-data', ]
) !!}

{!! $form->renderFields() !!}

<button type="submit" class="btn btn-primary" value="save" name="submit">Save</button>

@if (isset($createRoute))
   <button type="submit" class="btn btn-primary" value="save-and-add" name="submit">Save and Add New</button>
@endif

<?php $resourceConfig = $controller->getResourceConfig() ?>
@if(isset($resourceConfig['parentModel']) AND $foreignKeyValue = request()->query($resourceConfig['parentModel']['foreignKey']))
   <a href="{{ route($resourceConfig['id'].'.index').'?'.$resourceConfig['parentModel']['foreignKey'].'='.$foreignKeyValue }}"
      class="back-to-filtered-listing">Back to listing</a>

   {!! Form::hidden('parent_model', '?'.$resourceConfig['parentModel']['foreignKey'].'='.$foreignKeyValue) !!}
@endif
{{-- {!! $record->adminPreviewButton() !!} --}}

{!! Form::close() !!}