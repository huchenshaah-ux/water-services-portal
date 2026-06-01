<?php

namespace App\Http\Controllers;

use App\Imports\ApplicationsImport;
use App\Exports\ApplicationsExport;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

        $import = new ApplicationsImport;
        Excel::import($import, $request->file('file'));

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

    public function export(Request $request): BinaryFileResponse
    {
        AuditService::log('excel.export');

        $filename = 'applications-'.now()->format('Y-m-d-His').'.xlsx';

        return Excel::download(
            new ApplicationsExport($request->only(['status', 'service_category'])),
            $filename
        );
    }
}
