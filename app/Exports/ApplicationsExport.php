<?php

namespace App\Exports;

use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApplicationsExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private array $filters = []) {}

    public function query()
    {
        return Application::query()
            ->when($this->filters['status'] ?? null, fn ($q, $s) => $q->where('status', $s))
            ->when($this->filters['service_category'] ?? null, fn ($q, $c) => $q->where('service_category', $c))
            ->orderBy('application_date');
    }

    public function headings(): array
    {
        return [
            'Entry No',
            'Application Date',
            'Applicant Name',
            'ID Number',
            'Contact Number',
            'Address',
            'Service Address',
            'Billing Address',
            'Service Category',
            'Status',
            'Fenaka ID',
            'Remarks',
        ];
    }

    public function map($application): array
    {
        return [
            $application->entry_no,
            $application->application_date->format('Y-m-d'),
            $application->applicant_name,
            $application->id_number,
            $application->contact_number,
            $application->address,
            $application->service_address,
            $application->billing_address,
            $application->service_category,
            $application->status,
            $application->fenaka_id,
            $application->remarks,
        ];
    }
}
