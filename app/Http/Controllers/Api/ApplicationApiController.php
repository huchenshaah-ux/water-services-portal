<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $applications = Application::with('supervisor')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate($request->get('per_page', 20));

        return response()->json($applications);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $application = Application::create($data);
        AuditService::log('api.application.created', $application);

        return response()->json($application->load('supervisor'), 201);
    }

    public function update(Request $request, Application $application): JsonResponse
    {
        $application->update($this->validated($request, $application));
        AuditService::log('api.application.updated', $application);

        return response()->json($application->fresh()->load('supervisor'));
    }

    public function destroy(Application $application): JsonResponse
    {
        $application->delete();
        AuditService::log('api.application.deleted');

        return response()->json(['message' => 'Deleted']);
    }

    private function validated(Request $request, ?Application $application = null): array
    {
        return $request->validate([
            'entry_no' => 'required|string|max:50|unique:applications,entry_no,'.$application?->id,
            'application_date' => 'required|date',
            'applicant_name' => 'required|string|max:255',
            'id_number' => 'required|string|max:50',
            'contact_number' => 'required|string|max:30',
            'address' => 'required|string',
            'service_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
            'service_category' => 'required|in:'.implode(',', Application::SERVICE_CATEGORIES),
            'status' => 'required|in:'.implode(',', Application::STATUSES),
            'supervised_by' => 'nullable|exists:users,id',
            'fenaka_id' => 'nullable|string|max:50',
            'remarks' => 'nullable|string',
        ]);
    }
}
