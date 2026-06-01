<?php

namespace App\Imports;

use App\Models\Application;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ApplicationsImport implements ToModel, WithHeadingRow, WithValidation
{
    public int $imported = 0;

    public int $duplicates = 0;

    /** @var array<int, string> */
    public array $errors = [];

    public function model(array $row)
    {
        $entryNo = $this->value($row, ['entry_no', 'entry no', 'entryno']);

        if (! $entryNo) {
            $this->errors[] = 'Row missing entry number';

            return null;
        }

        if (Application::where('entry_no', $entryNo)->exists()) {
            $this->duplicates++;

            return null;
        }

        $this->imported++;

        return new Application([
            'entry_no' => $entryNo,
            'application_date' => $this->parseDate($this->value($row, ['application_date', 'application date', 'date'])),
            'applicant_name' => $this->value($row, ['applicant_name', 'applicant name', 'name']),
            'id_number' => $this->value($row, ['id_number', 'id number', 'id']),
            'contact_number' => $this->value($row, ['contact_number', 'contact number', 'contact', 'phone']),
            'address' => $this->value($row, ['address']),
            'service_address' => $this->value($row, ['service_address', 'service address']),
            'billing_address' => $this->value($row, ['billing_address', 'billing address']),
            'service_category' => $this->normalizeCategory($this->value($row, ['service_category', 'service category', 'category'])),
            'status' => $this->normalizeStatus($this->value($row, ['status'], 'pending')),
            'fenaka_id' => $this->value($row, ['fenaka_id', 'fenaka id']),
            'remarks' => $this->value($row, ['remarks', 'remark']),
        ]);
    }

    public function rules(): array
    {
        return [];
    }

    private function value(array $row, array $keys, ?string $default = null): ?string
    {
        foreach ($keys as $key) {
            $normalized = str_replace(' ', '_', strtolower($key));
            if (isset($row[$normalized]) && $row[$normalized] !== '') {
                return trim((string) $row[$normalized]);
            }
            if (isset($row[$key]) && $row[$key] !== '') {
                return trim((string) $row[$key]);
            }
        }

        return $default;
    }

    private function parseDate(?string $value): string
    {
        if (! $value) {
            return now()->toDateString();
        }

        try {
            if (is_numeric($value)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value))->toDateString();
            }

            return Carbon::parse($value)->toDateString();
        } catch (\Throwable) {
            return now()->toDateString();
        }
    }

    private function normalizeStatus(?string $status): string
    {
        $status = strtolower((string) $status);

        return in_array($status, Application::STATUSES, true) ? $status : 'pending';
    }

    private function normalizeCategory(?string $category): string
    {
        $category = strtolower(str_replace(' ', '_', (string) $category));

        return in_array($category, Application::SERVICE_CATEGORIES, true) ? $category : 'other';
    }
}
