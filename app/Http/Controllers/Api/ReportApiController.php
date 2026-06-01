<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ReportApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'totals' => [
                'all' => Application::count(),
                'pending' => Application::where('status', 'pending')->count(),
                'approved' => Application::where('status', 'approved')->count(),
                'connected' => Application::where('status', 'connected')->count(),
                'rejected' => Application::where('status', 'rejected')->count(),
            ],
            'by_status' => Application::select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status'),
            'by_category' => Application::select('service_category', DB::raw('COUNT(*) as total'))
                ->groupBy('service_category')
                ->pluck('total', 'service_category'),
            'monthly' => Application::select(
                DB::raw('DATE_FORMAT(application_date, "%Y-%m") as period'),
                DB::raw('COUNT(*) as total')
            )
                ->groupBy('period')
                ->orderBy('period')
                ->limit(12)
                ->get(),
        ]);
    }
}
