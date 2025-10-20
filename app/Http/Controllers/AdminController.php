<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
  public function showLoginForm()
  {
    return view('admin.login');
  }

  public function login(Request $request)
  {
    $credentials = $request->validate([
      'email' => ['required', 'email'],
      'password' => ['required'],
    ]);

    if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
      return redirect()->intended(route('admin.dashboard'));
    }

    return back()->withErrors([
      'email' => 'Invalid email or password.',
    ])->onlyInput('email');
  }

  public function logout(Request $request)
  {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('admin.login');
  }


  // dashboard
  public function dashboard(Request $request) {

    return view('admin.dashboard');
  }

  public function supportHelp() {
    return view('admin.support');
  }

  public function employeeReport(Request $request)
  {
    $view = $request->get('view', 'daily'); // daily, weekly, monthly
    $date = $request->get('date', Carbon::today()->format('Y-m-d'));

    $employees = Employee::with(['attendances', 'pickups', 'drop']) // eager load
    ->get();

    // Filter attendances based on view
    foreach ($employees as $emp) {
      $emp->filtered_attendances = $emp->attendances->filter(function($att) use ($view, $date) {
        $mark_in = Carbon::parse($att->mark_in);
        if($view === 'daily') return $mark_in->isSameDay(Carbon::parse($date));
        if($view === 'weekly') return $mark_in->isSameWeek(Carbon::parse($date));
        if($view === 'monthly') return $mark_in->isSameMonth(Carbon::parse($date));
        return true;
      });

      // Filter pickups/drops similarly
      $emp->filtered_pickups = $emp->pickups->filter(function($p) use ($view, $date) {
        $pickup_time = Carbon::parse($p->created_at);
        if($view === 'daily') return $pickup_time->isSameDay(Carbon::parse($date));
        if($view === 'weekly') return $pickup_time->isSameWeek(Carbon::parse($date));
        if($view === 'monthly') return $pickup_time->isSameMonth(Carbon::parse($date));
        return true;
      });
    }

    return view('admin.employee-report', compact('employees', 'view', 'date'));
  }

  public function attendanceReport(Request $request)
  {
    $view = $request->get('view', 'daily');

    $attendances = Attendance::with(['employee'])
      ->when($view === 'daily', fn($q) => $q->whereDate('mark_in', Carbon::today()))
      ->when($view === 'weekly', fn($q) => $q->whereBetween('mark_in', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]))
      ->when($view === 'monthly', fn($q) => $q->whereMonth('mark_in', Carbon::now()->month))
      ->orderBy('mark_in', 'desc')
      ->get();

    // Summary
    $summary = [
      'total_employees' => $attendances->pluck('employee_id')->unique()->count(),
      'total_mark_ins' => $attendances->count(),
      'total_hours' => $attendances->sum(function($a) {
        return floatval($a->hour ?? 0);
      }),
      'total_earning' => $attendances->sum('earning'),
    ];

    return view('admin.attendance-report', compact('attendances', 'summary', 'view'));
  }


}
