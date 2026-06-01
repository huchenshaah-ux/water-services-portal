<?php

namespace App\Http\Controllers;

use App\Exports\ApplicationsExport;
use App\Imports\ApplicationsImport;
use App\Models\Application;
use App\Services\AuditService;
use App\Support\VercelFeatures;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelController extends Controller
{
    public function importForm(): View
    {
        if (! auth()->user()?->canEditApplications()) {
            abort(403);
        }

        return view('applications.import');
    }

    public function import(Request $request): RedirectResponse
    {
        if (! auth()->user()?->canEditApplications()) {
            abort(403);
        }

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        if (VercelFeatures::hasExcel()) {
            $import = new ApplicationsImport;
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

            AuditService::log('excel.import', null, null, [
                'imported' => $import->imported,
                'duplicates' => $import->duplicates,
                'errors' => count($import->errors),
            ]);

            return redirect()->route('excel.import.form')->with([
                'import_summary' => [
                    'imported' => $import->imported,
                    'duplicates' => $import->duplicates,
                    'errors' => $import->errors,
                ],
                'success' => __('Import completed.'),
            ]);
        }

        return $this->importCsv($request);
    }

    public function export(Request $request): BinaryFileResponse|StreamedResponse
    {
        AuditService::log('excel.export');

        if (VercelFeatures::hasExcel()) {
            $filename = 'applications-'.now()->format('Y-m-d-His').'.xlsx';

            return \Maatwebsite\Excel\Facades\Excel::download(
                new ApplicationsExport($request->only(['status', 'service_category'])),
                $filename
            );
        }

        return $this->exportCsv($request);
    }

    private function importCsv(Request $request): RedirectResponse
    {
        $imported = 0;
        $duplicates = 0;
        $errors = [];
        $handle = fopen($request->file('file')->getRealPath(), 'r');
        $header = fgetcsv($handle);

        if (! $header) {
            return back()->with('error', __('Could not read CSV file.'));
        }

        $header = array_map(fn ($h) => strtolower(str_replace(' ', '_', trim($h))), $header);

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            if ($data === false) {
                continue;
            }
            $entryNo = $data['entry_no'] ?? null;
            if (! $entryNo) {
                $errors[] = 'Row missing entry_no';

                continue;
            }
            if (Application::where('entry_no', $entryNo)->exists()) {
                $duplicates++;

                continue;
            }
            try {
                Application::create([
                    'entry_no' => $entryNo,
                    'application_date' => $data['application_date'] ?? now()->toDateString(),
                    'applicant_name' => $data['applicant_name'] ?? 'Unknown',
                    'id_number' => $data['id_number'] ?? '',
                    'contact_number' => $data['contact_number'] ?? '',
                    'address' => $data['address'] ?? '',
                    'service_address' => $data['service_address'] ?? null,
                    'billing_address' => $data['billing_address'] ?? null,
                    'service_category' => $data['service_category'] ?? 'other',
                    'status' => $data['status'] ?? 'pending',
                    'fenaka_id' => $data['fenaka_id'] ?? null,
                    'remarks' => $data['remarks'] ?? null,
                ]);
                $imported++;
            } catch (\Throwable $e) {
                $errors[] = $entryNo.': '.$e->getMessage();
            }
        }
        fclose($handle);

        return redirect()->route('excel.import.form')->with([
            'import_summary' => compact('imported', 'duplicates', 'errors'),
            'success' => __('CSV import completed.'),
        ]);
    }

    private function exportCsv(Request $request): StreamedResponse
    {
        $filename = 'applications-'.now()->format('Y-m-d-His').'.csv';

        return Response::streamDownload(function () use ($request) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'entry_no', 'application_date', 'applicant_name', 'id_number',
                'contact_number', 'address', 'service_category', 'status',
            ]);

            Application::query()
                ->when($request->status, fn ($q, $s) => $q->where('status', $s))
                ->when($request->service_category, fn ($q, $c) => $q->where('service_category', $c))
                ->orderBy('application_date')
                ->chunk(200, function ($apps) use ($handle) {
                    foreach ($apps as $app) {
                        fputcsv($handle, [
                            $app->entry_no,
                            $app->application_date->format('Y-m-d'),
                            $app->applicant_name,
                            $app->id_number,
                            $app->contact_number,
                            $app->address,
                            $app->service_category,
                            $app->status,
                        ]);
                    }
                });
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
