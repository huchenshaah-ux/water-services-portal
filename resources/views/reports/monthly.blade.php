@extends('layouts.app')

@section('page_title', __('Monthly Report'))
@section('content')
<div class="card">
    <div class="card-header">
        <form method="GET" class="form-inline">
            <select name="month" class="form-control mr-1">
                @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" @selected($month == $m)>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                @endfor
            </select>
            <input type="number" name="year" value="{{ $year }}" class="form-control mr-2" style="width:100px">
            <button class="btn btn-primary">{{ __('Filter') }}</button>
        </form>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            @foreach($summary as $status => $total)
            <div class="col"><span class="badge badge-secondary">{{ ucfirst($status) }}: {{ $total }}</span></div>
            @endforeach
        </div>
        <table class="table table-bordered table-sm">
            <thead>
                <tr><th>{{ __('Date') }}</th><th>{{ __('Entry No') }}</th><th>{{ __('Applicant') }}</th><th>{{ __('Status') }}</th></tr>
            </thead>
            <tbody>
                @foreach($applications as $app)
                <tr>
                    <td>{{ $app->application_date->format('Y-m-d') }}</td>
                    <td>{{ $app->entry_no }}</td>
                    <td>{{ $app->applicant_name }}</td>
                    <td>{{ ucfirst($app->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
