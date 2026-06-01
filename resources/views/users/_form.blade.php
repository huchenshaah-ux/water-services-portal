<div class="form-group">
    <label>{{ __('Name') }}</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
</div>
<div class="form-group">
    <label>{{ __('Email') }}</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
</div>
<div class="form-group">
    <label>{{ __('Password') }} @isset($user)<small class="text-muted">({{ __('leave blank to keep') }})</small>@endisset</label>
    <input type="password" name="password" class="form-control" @empty($user) required @endempty>
</div>
<div class="form-group">
    <label>{{ __('Confirm Password') }}</label>
    <input type="password" name="password_confirmation" class="form-control">
</div>
<div class="form-group">
    <label>{{ __('Role') }}</label>
    <select name="role" class="form-control" required>
        @foreach(\App\Models\User::ROLES as $role)
        <option value="{{ $role }}" @selected(old('role', $user->role ?? 'staff') === $role)>{{ ucfirst($role) }}</option>
        @endforeach
    </select>
</div>
