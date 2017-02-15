@extends('ignicms::admin.layouts.default')

@section('pageTitle', $pageTitle)
@section('content')
    @yield('before.form')
    <div class="default-form">
        {{-- TODO action verb --}}
        <h3 class="box-title">{{ $pageTitle }}</h3>
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
    </div>
    <div class="after-default-form">
        @yield('after.form')
    </div>
@stop
