@extends('ignicms::admin.layouts.default')

@section('pageTitle', $pageTitle)
@section('content')
    @yield('before.form')
    <div class="default-form">
        <h3 class="box-title">{{ $pageTitle }} - {{ $form->getActionVerb() }}</h3>
        {!!  Form::open([
            'url' => action($form->getAction(), ['id' => $form->getModel()->getKey()]),
            'method' => $form->getMethod(),
            'role' => $form->getRole(),
            'enctype'=> $form->getEnctype() ?? 'multipart/form-data', ]
        ) !!}
       
       {!! $form->renderFields() !!}
       
        <button type="submit" class="btn btn-primary">Save</button>
        {{-- {!! $record->adminPreviewButton() !!} --}}

        {!! Form::close() !!}
    </div>
    <div class="after-default-form">
        @yield('after.form')
    </div>
@stop
