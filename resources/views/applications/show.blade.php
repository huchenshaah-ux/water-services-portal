@extends('layouts.app')

@section('page_title', $application->entry_no)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('applications.index') }}">{{ __('messages.applications') }}</a></li>
    <li class="breadcrumb-item active">{{ $application->entry_no }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Application Details') }}</h3>
                <div class="card-tools">
                    <span class="badge badge-{{ $application->status_badge_class }}">{{ ucfirst($application->status) }}</span>
                </div>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">{{ __('Entry No') }}</dt><dd class="col-sm-8">{{ $application->entry_no }}</dd>
                    <dt class="col-sm-4">{{ __('Application Date') }}</dt><dd class="col-sm-8">{{ $application->application_date->format('d M Y') }}</dd>
                    <dt class="col-sm-4">{{ __('Applicant Name') }}</dt><dd class="col-sm-8">{{ $application->applicant_name }}</dd>
                    <dt class="col-sm-4">{{ __('ID Number') }}</dt><dd class="col-sm-8">{{ $application->id_number }}</dd>
                    <dt class="col-sm-4">{{ __('Contact') }}</dt>
                    <dd class="col-sm-8">
                        {{ $application->contact_number }}
                        <a href="{{ $application->whatsapp_url }}" target="_blank" class="btn btn-success btn-xs ml-2">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    </dd>
                    <dt class="col-sm-4">{{ __('Address') }}</dt><dd class="col-sm-8">{{ $application->address }}</dd>
                    <dt class="col-sm-4">{{ __('Service Address') }}</dt><dd class="col-sm-8">{{ $application->service_address ?? '—' }}</dd>
                    <dt class="col-sm-4">{{ __('Billing Address') }}</dt><dd class="col-sm-8">{{ $application->billing_address ?? '—' }}</dd>
                    <dt class="col-sm-4">{{ __('Service Category') }}</dt><dd class="col-sm-8">{{ str_replace('_', ' ', ucfirst($application->service_category)) }}</dd>
                    <dt class="col-sm-4">{{ __('Supervised By') }}</dt><dd class="col-sm-8">{{ $application->supervisor?->name ?? '—' }}</dd>
                    <dt class="col-sm-4">{{ __('Fenaka ID') }}</dt><dd class="col-sm-8">{{ $application->fenaka_id ?? '—' }}</dd>
                    <dt class="col-sm-4">{{ __('Remarks') }}</dt><dd class="col-sm-8">{{ $application->remarks ?? '—' }}</dd>
                </dl>
            </div>
            <div class="card-footer">
                @if(auth()->user()->canEditApplications())
                <a href="{{ route('applications.edit', $application) }}" class="btn btn-warning"><i class="fas fa-edit"></i> {{ __('Edit') }}</a>
                @endif
                <a href="{{ route('applications.pdf', $application) }}" class="btn btn-danger"><i class="fas fa-file-pdf"></i> PDF</a>
                <button onclick="window.print()" class="btn btn-secondary"><i class="fas fa-print"></i> {{ __('Print') }}</button>
                @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                <form action="{{ route('applications.destroy', $application) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this application?') }}')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger float-right"><i class="fas fa-trash"></i></button>
                </form>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-header">{{ __('QR Code') }}</div>
            <div class="card-body">
                <img src="{{ route('applications.qr', $application) }}" alt="QR" class="img-fluid" style="max-width:200px">
                <p class="text-muted small mt-2">{{ __('Scan to view application') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
