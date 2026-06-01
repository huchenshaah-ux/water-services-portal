@extends('layouts.app')

@section('title', __('messages.dashboard'))
@section('page_title', __('messages.dashboard'))
@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('messages.dashboard') }}</li>
@endsection

@section('content')
<div class="row">
    @foreach([
        ['label' => __('messages.total_applications'), 'value' => $stats['total'], 'icon' => 'fa-file-alt', 'color' => 'info'],
        ['label' => __('messages.pending'), 'value' => $stats['pending'], 'icon' => 'fa-clock', 'color' => 'warning'],
        ['label' => __('messages.approved'), 'value' => $stats['approved'], 'icon' => 'fa-check', 'color' => 'success'],
        ['label' => __('messages.connected'), 'value' => $stats['connected'], 'icon' => 'fa-plug', 'color' => 'primary'],
        ['label' => __('messages.rejected'), 'value' => $stats['rejected'], 'icon' => 'fa-times', 'color' => 'danger'],
    ] as $card)
    <div class="col-lg-2 col-md-4 col-6">
        <div class="small-box bg-{{ $card['color'] }}">
            <div class="inner">
                <h3>{{ number_format($card['value']) }}</h3>
                <p>{{ $card['label'] }}</p>
            </div>
            <div class="icon"><i class="fas {{ $card['icon'] }}"></i></div>
        </div>
    </div>
    @endforeach
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">{{ __('Monthly Applications') }}</h3></div>
            <div class="card-body"><canvas id="monthlyChart" height="100"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">{{ __('By Status') }}</h3></div>
            <div class="card-body"><canvas id="statusChart"></canvas></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">{{ __('By Service Type') }}</h3></div>
            <div class="card-body"><canvas id="categoryChart" height="120"></canvas></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">{{ __('Staff Performance') }}</h3></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>{{ __('Staff') }}</th><th>{{ __('Applications') }}</th></tr></thead>
                    <tbody>
                        @forelse($staffPerformance as $staff)
                        <tr>
                            <td>{{ $staff->name }}</td>
                            <td><span class="badge badge-primary">{{ $staff->applications_count }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center text-muted">{{ __('No data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h3 class="card-title">{{ __('messages.recent_applications') }}</h3></div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Entry No') }}</th>
                            <th>{{ __('Applicant') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentApplications as $app)
                        <tr>
                            <td><a href="{{ route('applications.show', $app) }}">{{ $app->entry_no }}</a></td>
                            <td>{{ $app->applicant_name }}</td>
                            <td><span class="badge badge-{{ $app->status_badge_class }}">{{ ucfirst($app->status) }}</span></td>
                            <td>{{ $app->application_date->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><h3 class="card-title">{{ __('messages.staff_activity') }}</h3></div>
            <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                <ul class="list-group list-group-flush">
                    @foreach($activityLog as $log)
                    <li class="list-group-item">
                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small><br>
                        <strong>{{ $log->user?->name ?? 'System' }}</strong> — {{ $log->action }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthly = @json($monthlyChart);
    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: monthly.map(r => r.label),
            datasets: [{ label: '{{ __("Applications") }}', data: monthly.map(r => r.total), backgroundColor: '#007bff' }]
        }
    });
    const statusData = @json($statusChart);
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData),
            datasets: [{ data: Object.values(statusData), backgroundColor: ['#ffc107','#28a745','#17a2b8','#dc3545'] }]
        }
    });
    const catData = @json($categoryChart);
    new Chart(document.getElementById('categoryChart'), {
        type: 'pie',
        data: {
            labels: Object.keys(catData),
            datasets: [{ data: Object.values(catData) }]
        }
    });
});
</script>
@endpush
