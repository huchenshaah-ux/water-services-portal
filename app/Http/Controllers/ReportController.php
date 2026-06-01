<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Support\VercelFeatures;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('reports.index');
    }

    public function daily(Request $request): View
    {
        $date = $request->get('date', now()->toDateString());
        $applications = Application::whereDate('application_date', $date)
            ->with('supervisor')
            ->orderBy('entry_no')
            ->get();

        return view('reports.daily', compact('applications', 'date'));
    }

    public function monthly(Request $request): View
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $applications = Application::whereYear('application_date', $year)
            ->whereMonth('application_date', $month)
            ->with('supervisor')
            ->orderBy('application_date')
            ->get();

        $summary = Application::select('status', DB::raw('COUNT(*) as total'))
            ->whereYear('application_date', $year)
            ->whereMonth('application_date', $month)
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('reports.monthly', compact('applications', 'month', 'year', 'summary'));
    }

    public function connections(Request $request): View
    {
        $applications = Application::where('status', 'connected')
            ->when($request->get('from'), fn ($q, $from) => $q->whereDate('application_date', '>=', $from))
            ->when($request->get('to'), fn ($q, $to) => $q->whereDate('application_date', '<=', $to))
            ->with('supervisor')
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('reports.connections', compact('applications'));
    }

    public function categories(): View
    {
        $data = Application::select('service_category', 'status', DB::raw('COUNT(*) as total'))
            ->groupBy('service_category', 'status')
            ->get()
            ->groupBy('service_category');

        return view('reports.categories', compact('data'));
    }

    public function print(Request $request): Response
    {
        $type = $request->get('type', 'daily');
        $view = match ($type) {
            'monthly' => 'reports.monthly',
            'connections' => 'reports.connections',
            'categories' => 'reports.categories',
            default => 'reports.daily',
        };

        if ($type === 'daily') {
            $date = $request->get('date', now()->toDateString());
            $applications = Application::whereDate('application_date', $date)->get();

            if (VercelFeatures::hasPdf()) {
                return \Barryvdh\DomPDF\Facade\Pdf::loadView($view, compact('applications', 'date'))->stream('report.pdf');
            }

            return view($view, compact('applications', 'date'));
        }

        abort(404);
    }
}
