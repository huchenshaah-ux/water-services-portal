@extends('layouts.app')

@section('page_title', __('Service Category Reports'))
@section('content')
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ __('Category') }}</th>
                    @foreach(\App\Models\Application::STATUSES as $status)
                    <th>{{ ucfirst($status) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $category => $rows)
                <tr>
                    <td><strong>{{ str_replace('_', ' ', ucfirst($category)) }}</strong></td>
                    @foreach(\App\Models\Application::STATUSES as $status)
                    <td>{{ $rows->firstWhere('status', $status)?->total ?? 0 }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
