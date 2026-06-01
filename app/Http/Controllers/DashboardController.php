<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total' => Application::count(),
            'pending' => Application::where('status', 'pending')->count(),
            'approved' => Application::where('status', 'approved')->count(),
            'connected' => Application::where('status', 'connected')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
        ];

        $monthlyChart = Application::select(
            DB::raw('MONTH(application_date) as month'),
            DB::raw('YEAR(application_date) as year'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('application_date', now()->year)
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get()
            ->map(fn ($row) => [
                'label' => date('M', mktime(0, 0, 0, (int) $row->month, 1)),
                'total' => $row->total,
            ]);

        $statusChart = Application::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $categoryChart = Application::select('service_category', DB::raw('COUNT(*) as total'))
            ->groupBy('service_category')
            ->pluck('total', 'service_category');

        $recentApplications = Application::with('supervisor')
            ->latest()
            ->limit(10)
            ->get();

        $activityLog = AuditLog::with('user')
            ->latest()
            ->limit(15)
            ->get();

        $staffPerformance = User::whereIn('role', ['staff', 'supervisor'])
            ->withCount(['supervisedApplications as applications_count'])
            ->orderByDesc('applications_count')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'monthlyChart',
            'statusChart',
            'categoryChart',
            'recentApplications',
            'activityLog',
            'staffPerformance'
        ));
    }
}
