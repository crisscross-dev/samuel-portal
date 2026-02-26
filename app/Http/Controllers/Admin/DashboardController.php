<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Faculty;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_students'    => Student::count(),
            'total_faculty'     => Faculty::count(),
            'total_users'       => User::count(),
            'active_enrollments' => Enrollment::where('status', 'enrolled')->count(),
            'pending_payments'  => Payment::where('status', 'pending')->count(),
            'total_revenue'     => Payment::where('status', 'verified')->sum('amount'),
        ];

        $recentUsers = User::with('roles')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }
}
