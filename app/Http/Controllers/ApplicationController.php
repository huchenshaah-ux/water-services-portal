<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use App\Services\AuditService;
use App\Support\VercelFeatures;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Application::with('supervisor')->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('entry_no', 'like', "%{$search}%")
                    ->orWhere('applicant_name', 'like', "%{$search}%")
                    ->orWhere('id_number', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($category = $request->get('service_category')) {
            $query->where('service_category', $category);
        }

        $applications = $query->paginate(15)->withQueryString();

        return view('applications.index', compact('applications'));
    }

    public function create(): View
    {
        $this->authorizeEdit();
        $supervisors = User::whereIn('role', ['admin', 'supervisor', 'staff'])->orderBy('name')->get();

        return view('applications.create', compact('supervisors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeEdit();
        $data = $this->validated($request);
        $application = Application::create($data);
        AuditService::log('application.created', $application, null, $application->toArray());

        return redirect()->route('applications.show', $application)
            ->with('success', __('Application created successfully.'));
    }

    public function show(Application $application): View
    {
        $application->load('supervisor');

        return view('applications.show', compact('application'));
    }

    public function edit(Application $application): View
    {
        $this->authorizeEdit();
        $supervisors = User::whereIn('role', ['admin', 'supervisor', 'staff'])->orderBy('name')->get();

        return view('applications.edit', compact('application', 'supervisors'));
    }

    public function update(Request $request, Application $application): RedirectResponse
    {
        $this->authorizeEdit();
        $old = $application->toArray();
        $application->update($this->validated($request));
        AuditService::log('application.updated', $application, $old, $application->fresh()->toArray());

        return redirect()->route('applications.show', $application)
            ->with('success', __('Application updated successfully.'));
    }

    public function destroy(Application $application): RedirectResponse
    {
        $this->authorizeEdit();
        if (! auth()->user()->isAdmin() && ! auth()->user()->isSupervisor()) {
            abort(403);
        }
        $old = $application->toArray();
        $application->delete();
        AuditService::log('application.deleted', null, $old, null);

        return redirect()->route('applications.index')
            ->with('success', __('Application deleted successfully.'));
    }

    public function pdf(Application $application)
    {
        $application->load('supervisor');

        if (VercelFeatures::hasPdf()) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('applications.pdf', compact('application'));

            return $pdf->download("application-{$application->entry_no}.pdf");
        }

        return view('applications.pdf', compact('application'));
    }

    public function qr(Application $application): RedirectResponse|Response
    {
        $url = route('applications.show', $application);

        if (VercelFeatures::hasQrCode()) {
            $qr = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(200)->generate($url);

            return response($qr)->header('Content-Type', 'image/svg+xml');
        }

        return redirect()->away(
            'https://api.qrserver.com/v1/create-qr-code/?'.http_build_query([
                'size' => '200x200',
                'data' => $url,
            ])
        );
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'entry_no' => 'required|string|max:50|unique:applications,entry_no,'.($request->route('application')?->id ?? 'NULL'),
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

    private function authorizeEdit(): void
    {
        if (! auth()->user()?->canEditApplications()) {
            abort(403);
        }
    }
}
