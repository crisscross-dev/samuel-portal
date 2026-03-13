<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'The provided credentials do not match our records.']);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been deactivated. Please contact the administrator.']);
        }

        return $this->redirectBasedOnRole($user);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirect user to their role-specific dashboard.
     */
    private function redirectBasedOnRole($user): RedirectResponse
    {
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('registrar')) {
            return redirect()->route('registrar.dashboard');
        }

        if ($user->hasRole('faculty')) {
            return redirect()->route('faculty.dashboard');
        }

        if ($user->hasRole('student')) {
            return redirect()->route('student.dashboard');
        }

        if ($user->hasRole('guidance')) {
            return redirect()->route('guidance.dashboard');
        }

        if ($user->hasRole('cashier')) {
            return redirect()->route('cashier.dashboard');
        }

        // Fallback
        return redirect('/');
    }
}
