@extends('layouts.app')

@section('page_title', __('Add Application'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('applications.index') }}">{{ __('messages.applications') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Add') }}</li>
@endsection

@section('content')
<div class="card card-primary">
    <form action="{{ route('applications.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('applications._form', ['application' => new \App\Models\Application])
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            <a href="{{ route('applications.index') }}" class="btn btn-default">{{ __('Cancel') }}</a>
        </div>
    </form>
</div>
@endsection
