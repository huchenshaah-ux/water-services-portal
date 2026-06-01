@extends('layouts.app')

@section('page_title', __('messages.import_excel'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('applications.index') }}">{{ __('messages.applications') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Import') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header"><h3 class="card-title">{{ __('Upload Excel File') }}</h3></div>
            <form action="{{ route('excel.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <p class="text-muted">{{ __('Upload') }}: <strong>Applications for water services.xlsx</strong></p>
                    <div class="form-group">
                        <input type="file" name="file" class="form-control-file" accept=".xlsx,.xls,.csv" required>
                        @error('file')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <ul class="text-sm text-muted">
                        <li>{{ __('Reads rows automatically') }}</li>
                        <li>{{ __('Validates data') }}</li>
                        <li>{{ __('Detects duplicates by entry number') }}</li>
                    </ul>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> {{ __('Import') }}</button>
                </div>
            </form>
        </div>
    </div>
    @if(session('import_summary'))
    <div class="col-md-6">
        <div class="card card-success">
            <div class="card-header"><h3 class="card-title">{{ __('Import Summary') }}</h3></div>
            <div class="card-body">
                <p><strong>{{ __('Imported') }}:</strong> {{ session('import_summary.imported') }}</p>
                <p><strong>{{ __('Duplicates Skipped') }}:</strong> {{ session('import_summary.duplicates') }}</p>
                @if(count(session('import_summary.errors')))
                <p><strong>{{ __('Errors') }}:</strong></p>
                <ul class="text-danger small">
                    @foreach(session('import_summary.errors') as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
