@extends('layouts.app')

@section('page_title', __('messages.settings'))
@section('content')
<div class="card col-md-6">
    <form action="{{ route('settings.update') }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>{{ __('Theme') }}</label>
                <select name="theme" class="form-control">
                    <option value="light" @selected(session('theme', 'light') === 'light')>{{ __('Light') }}</option>
                    <option value="dark" @selected(session('theme') === 'dark')>{{ __('Dark') }}</option>
                </select>
            </div>
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="notifications_email" name="notifications_email" value="1" @checked(session('notifications_email'))>
                    <label class="custom-control-label" for="notifications_email">{{ __('Email Notifications') }}</label>
                </div>
            </div>
            <p class="text-muted small">WhatsApp: {{ $whatsapp }}</p>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ __('Save Settings') }}</button>
        </div>
    </form>
</div>
@endsection
