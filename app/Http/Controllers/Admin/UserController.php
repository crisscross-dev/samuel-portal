<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\Faculty;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('roles');

        if ($request->filled('role')) {
            $query->whereHas('roles', fn ($q) => $q->where('slug', $request->role));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        $roles = Role::whereIn('slug', ['registrar', 'faculty'])->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        $user->assignRole($request->role);

        // If faculty role, create faculty profile
        if ($request->role === 'faculty') {
            Faculty::create([
                'user_id'     => $user->id,
                'employee_id' => 'EMP-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $user->load('roles');
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role'  => ['required', 'string', 'exists:roles,slug'],
            'is_active' => ['boolean'],
        ]);

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => $request->password]);
        }

        // Sync role
        $role = Role::where('slug', $request->role)->first();
        $user->roles()->sync([$role->id]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
