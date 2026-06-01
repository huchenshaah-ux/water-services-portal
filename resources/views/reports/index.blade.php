@extends('layouts.app')

@section('page_title', __('messages.reports'))
@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('messages.reports') }}</li>
@endsection

@section('content')
<div class="row">
    @foreach([
        ['route' => 'reports.daily', 'title' => __('Daily Reports'), 'icon' => 'fa-calendar-day', 'color' => 'primary'],
        ['route' => 'reports.monthly', 'title' => __('Monthly Reports'), 'icon' => 'fa-calendar-alt', 'color' => 'info'],
        ['route' => 'reports.connections', 'title' => __('Connection Reports'), 'icon' => 'fa-plug', 'color' => 'success'],
        ['route' => 'reports.categories', 'title' => __('Service Category Reports'), 'icon' => 'fa-list', 'color' => 'warning'],
    ] as $report)
    <div class="col-md-3 col-sm-6">
        <a href="{{ route($report['route']) }}" class="text-decoration-none">
            <div class="info-box bg-{{ $report['color'] }}">
                <span class="info-box-icon"><i class="fas {{ $report['icon'] }}"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-white">{{ $report['title'] }}</span>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>
@endsection
