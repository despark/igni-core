@extends('ignicms::admin.layouts.default')

@section('pageTitle', $pageTitle)
@section('content')
    @yield('before.form')
    <div class="default-form">
        <h3 class="box-title">{{ $pageTitle }}</h3>
        @include('ignicms::admin.formElements.form')
    </div>
    <div class="after-default-form">
        @yield('after.form')
    </div>
@stop
