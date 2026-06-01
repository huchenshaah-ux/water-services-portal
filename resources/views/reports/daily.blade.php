@extends('layouts.app')

@section('page_title', __('Daily Report'))
@section('content')
<div class="card">
    <div class="card-header">
        <form method="GET" class="form-inline">
            <input type="date" name="date" value="{{ $date }}" class="form-control mr-2">
            <button class="btn btn-primary">{{ __('Filter') }}</button>
            <a href="{{ route('reports.print', ['type' => 'daily', 'date' => $date]) }}" class="btn btn-danger ml-2" target="_blank"><i class="fas fa-file-pdf"></i> PDF</a>
        </form>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ __('Entry No') }}</th>
                    <th>{{ __('Applicant') }}</th>
                    <th>{{ __('Category') }}</th>
                    <th>{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr>
                    <td>{{ $app->entry_no }}</td>
                    <td>{{ $app->applicant_name }}</td>
                    <td>{{ $app->service_category }}</td>
                    <td>{{ ucfirst($app->status) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">{{ __('No records for this date') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
