@extends('ignicms::admin.layouts.default')

@section('pageTitle', $pageTitle)
@section('content')
    @yield('before.form')
    <div class="default-form">
        <h3 class="box-title">{{ $pageTitle }} - {{ $actionVerb or 'Edit'  }}</h3>
        {!!  Form::open([
            'url' => route($form->getAction()),
            'method' => (isset($form->getMethod())) ? $form->getMethod() : 'POST',
            'role' => $form->getRole(),
            'enctype'=> (isset($form->getEnctype())) ? $form->getEnctype() : 'multipart/form-data', ]
        ) !!}
       {{--  {!! $record->buildForm() !!} --}}
        @foreach ($form->getFields() as $field)
            {!! $field->toHtml() !!}
        @endforeach

        <button type="submit" class="btn btn-primary">Save</button>
        {!! $record->adminPreviewButton() !!}

        {!! Form::close() !!}
    </div>
    <div class="after-default-form">
        @yield('after.form')
    </div>
@stop
