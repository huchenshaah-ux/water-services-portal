@extends('layouts.app')

@section('page_title', __('Edit User'))
@section('content')
<div class="card">
    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            @include('users._form')
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-warning">{{ __('Update') }}</button>
        </div>
    </form>
</div>
@endsection
