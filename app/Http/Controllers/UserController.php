<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('name')->paginate(15);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        AuditService::log('user.created', $user);

        return redirect()->route('users.index')->with('success', __('User created successfully.'));
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validated($request, $user);
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $old = $user->toArray();
        $user->update($data);
        AuditService::log('user.updated', $user, $old, $user->fresh()->toArray());

        return redirect()->route('users.index')->with('success', __('User updated successfully.'));
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', __('You cannot delete your own account.'));
        }
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }
        AuditService::log('user.deleted', null, $user->toArray(), null);
        $user->delete();

        return redirect()->route('users.index')->with('success', __('User deleted successfully.'));
    }

    private function validated(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.($user?->id ?? 'NULL'),
            'password' => ($user ? 'nullable' : 'required').'|string|min:8|confirmed',
            'role' => 'required|in:'.implode(',', User::ROLES),
        ]);
    }
}
