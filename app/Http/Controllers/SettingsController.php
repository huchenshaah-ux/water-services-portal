<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        if (! auth()->user()?->isAdmin()) {
            abort(403);
        }

        return view('settings.index', [
            'whatsapp' => config('services.whatsapp.number'),
            'mail_from' => config('mail.from.address'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        if (! auth()->user()?->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'theme' => 'nullable|in:light,dark',
            'notifications_email' => 'nullable|boolean',
        ]);

        session([
            'theme' => $request->get('theme', 'light'),
            'notifications_email' => $request->boolean('notifications_email'),
        ]);

        return back()->with('success', __('Settings saved.'));
    }
}
