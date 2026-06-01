<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>{{ __('Entry No') }} *</label>
            <input type="text" name="entry_no" class="form-control @error('entry_no') is-invalid @enderror" value="{{ old('entry_no', $application->entry_no ?? '') }}" required>
            @error('entry_no')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>{{ __('Application Date') }} *</label>
            <input type="date" name="application_date" class="form-control" value="{{ old('application_date', isset($application) ? $application->application_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>{{ __('Status') }} *</label>
            <select name="status" class="form-control" required>
                @foreach(\App\Models\Application::STATUSES as $status)
                <option value="{{ $status }}" @selected(old('status', $application->status ?? 'pending') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ __('Applicant Name') }} *</label>
            <input type="text" name="applicant_name" class="form-control" value="{{ old('applicant_name', $application->applicant_name ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>{{ __('ID Number') }} *</label>
            <input type="text" name="id_number" class="form-control" value="{{ old('id_number', $application->id_number ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>{{ __('Contact Number') }} *</label>
            <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $application->contact_number ?? '') }}" required>
        </div>
    </div>
</div>
<div class="form-group">
    <label>{{ __('Address') }} *</label>
    <textarea name="address" class="form-control" rows="2" required>{{ old('address', $application->address ?? '') }}</textarea>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ __('Service Address') }}</label>
            <textarea name="service_address" class="form-control" rows="2">{{ old('service_address', $application->service_address ?? '') }}</textarea>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ __('Billing Address') }}</label>
            <textarea name="billing_address" class="form-control" rows="2">{{ old('billing_address', $application->billing_address ?? '') }}</textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>{{ __('Service Category') }} *</label>
            <select name="service_category" class="form-control" required>
                @foreach(\App\Models\Application::SERVICE_CATEGORIES as $cat)
                <option value="{{ $cat }}" @selected(old('service_category', $application->service_category ?? '') === $cat)>{{ str_replace('_', ' ', ucfirst($cat)) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>{{ __('Supervised By') }}</label>
            <select name="supervised_by" class="form-control">
                <option value="">{{ __('— None —') }}</option>
                @foreach($supervisors as $user)
                <option value="{{ $user->id }}" @selected(old('supervised_by', $application->supervised_by ?? '') == $user->id)>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>{{ __('Fenaka ID') }}</label>
            <input type="text" name="fenaka_id" class="form-control" value="{{ old('fenaka_id', $application->fenaka_id ?? '') }}">
        </div>
    </div>
</div>
<div class="form-group">
    <label>{{ __('Remarks') }}</label>
    <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $application->remarks ?? '') }}</textarea>
</div>
