@extends('layouts.app')

@section('page_title', __('messages.profile'))
@section('content')
<div class="card col-md-6">
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>{{ __('Name') }}</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="form-group">
                <label>{{ __('Email') }}</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="form-group">
                <label>{{ __('New Password') }}</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <label>{{ __('Confirm Password') }}</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <p class="text-muted">{{ __('Role') }}: <strong>{{ ucfirst($user->role) }}</strong></p>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ __('Update Profile') }}</button>
        </div>
    </form>
</div>
@endsection
