@extends('layouts.app')

@section('page_title', __('Connection Reports'))
@section('content')
<div class="card">
    <div class="card-header">
        <form method="GET" class="form-inline">
            <input type="date" name="from" value="{{ request('from') }}" class="form-control mr-1" placeholder="{{ __('From') }}">
            <input type="date" name="to" value="{{ request('to') }}" class="form-control mr-1" placeholder="{{ __('To') }}">
            <button class="btn btn-primary">{{ __('Filter') }}</button>
        </form>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-striped">
            <thead>
                <tr><th>{{ __('Entry No') }}</th><th>{{ __('Applicant') }}</th><th>{{ __('Fenaka ID') }}</th><th>{{ __('Date') }}</th></tr>
            </thead>
            <tbody>
                @foreach($applications as $app)
                <tr>
                    <td><a href="{{ route('applications.show', $app) }}">{{ $app->entry_no }}</a></td>
                    <td>{{ $app->applicant_name }}</td>
                    <td>{{ $app->fenaka_id }}</td>
                    <td>{{ $app->application_date->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $applications->links() }}
    </div>
</div>
@endsection
