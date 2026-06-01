@extends('layouts.app')

@section('page_title', __('Add User'))
@section('content')
<div class="card">
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('users._form')
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </div>
    </form>
</div>
@endsection
