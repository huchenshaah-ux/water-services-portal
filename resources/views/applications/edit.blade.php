@extends('layouts.app')

@section('page_title', __('Edit Application'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('applications.index') }}">{{ __('messages.applications') }}</a></li>
    <li class="breadcrumb-item active">{{ $application->entry_no }}</li>
@endsection

@section('content')
<div class="card card-warning">
    <form action="{{ route('applications.update', $application) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            @include('applications._form')
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-warning">{{ __('Update') }}</button>
            <a href="{{ route('applications.show', $application) }}" class="btn btn-default">{{ __('Cancel') }}</a>
        </div>
    </form>
</div>
@endsection
