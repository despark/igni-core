@extends('ignicms::admin.layouts.default')

@section('pageTitle', $pageTitle)

@section('content')
    <div class="default-form">
        <h3 class="box-title">{{ $pageTitle }} - {{ $actionVerb or 'Edit'  }}</h3>
        {!!  Form::open([
            'url'=>route($formAction, ['id' => $record->id]),
            'method' => (isset($formMethod)) ? $formMethod : 'POST',
            'role' => 'form',
            'enctype'=> 'multipart/form-data', ]
        ) !!}
        {!! $record->buildForm() !!}

        <button type="submit" class="btn btn-primary">Save</button>
        {!! $record->adminPreviewButton() !!}

        {!! Form::close() !!}
    </div>
    <div class="after-default-form">
        @yield('after.form')
    </div>
@stop

@push('additionalScripts')
