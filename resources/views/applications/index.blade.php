@extends('layouts.app')

@section('page_title', __('messages.applications'))
@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('messages.applications') }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                @if(auth()->user()->canEditApplications())
                <a href="{{ route('applications.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> {{ __('Add') }}</a>
                <a href="{{ route('excel.import.form') }}" class="btn btn-secondary btn-sm"><i class="fas fa-upload"></i> {{ __('Import') }}</a>
                @endif
                <a href="{{ route('excel.export', request()->only(['status','service_category'])) }}" class="btn btn-success btn-sm"><i class="fas fa-download"></i> {{ __('Export') }}</a>
            </div>
            <div class="col-md-6">
                <form method="GET" class="form-inline float-md-right">
                    <input type="text" name="search" class="form-control form-control-sm mr-1" placeholder="{{ __('Search...') }}" value="{{ request('search') }}">
                    <select name="status" class="form-control form-control-sm mr-1">
                        <option value="">{{ __('All Status') }}</option>
                        @foreach(\App\Models\Application::STATUSES as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-info">{{ __('Filter') }}</button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table id="applications-table" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>{{ __('Entry No') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Applicant') }}</th>
                    <th>{{ __('Category') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $app)
                <tr>
                    <td>{{ $app->entry_no }}</td>
                    <td>{{ $app->application_date->format('Y-m-d') }}</td>
                    <td>{{ $app->applicant_name }}</td>
                    <td>{{ str_replace('_', ' ', $app->service_category) }}</td>
                    <td><span class="badge badge-{{ $app->status_badge_class }}">{{ ucfirst($app->status) }}</span></td>
                    <td>
                        <a href="{{ route('applications.show', $app) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                        @if(auth()->user()->canEditApplications())
                        <a href="{{ route('applications.edit', $app) }}" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $applications->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>$('#applications-table').DataTable({ paging: false, info: false, searching: false });</script>
@endpush
