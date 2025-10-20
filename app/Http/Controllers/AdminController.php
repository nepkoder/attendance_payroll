<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\VehiclePickup;
use Carbon\Carbon;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
  public function showLoginForm()
  {
    return view('admin.login');
  }

  public function login(Request $request)
  {
    $request->validate([
      'username' => 'required|string',
      'password' => 'required|string',
    ]);

    $credentials = $request->only('username', 'password');

    // Attempt login using username instead of email
    if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
      return redirect()->intended(route('admin.dashboard'));
    }

    return back()->withErrors([
      'username' => 'Invalid username or password.',
    ])->onlyInput('username');
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
      $today = Carbon::today();

      // Total stats
      $totalEmployees = Employee::count();

    $attendances = Attendance::all();
    $totalSeconds = $attendances->sum(function ($att) {
      if ($att->mark_in && $att->mark_out) {
        return Carbon::parse($att->mark_in)->diffInSeconds(Carbon::parse($att->mark_out));
      }
      return 0;
    });

    $totalHours = round($totalSeconds / 3600, 2);

      $totalEarnings = Attendance::sum('earning');
      $totalPickups = VehiclePickup::count();
      $totalDrops = VehiclePickup::whereHas('drop')->count();

      // Online / Away employees
      $onlineEmployees = Employee::whereHas('attendances', function($q){
        $q->whereNull('mark_out');
      })->count();
      $awayEmployees = $totalEmployees - $onlineEmployees;

      // Today's stats
    $todayAttendances = Attendance::whereDate('mark_in', $today)->get();

    $todayAttendanceSeconds = $todayAttendances->sum(function ($att) {
      if ($att->mark_in && $att->mark_out) {
        return Carbon::parse($att->mark_in)->diffInSeconds(Carbon::parse($att->mark_out));
      }
      return 0;
    });

    $todayHours = round($todayAttendanceSeconds / 3600, 2);

      $todayEarnings = Attendance::whereDate('mark_in', $today)->sum('earning');
      $todayPickups = VehiclePickup::whereDate('created_at', $today)->count();
      $todayDrops = VehiclePickup::whereHas('drop', function($q) use ($today) {
        $q->whereDate('created_at', $today);
      })->count();

      $todayEmployeesIn = Attendance::whereDate('mark_in', $today)
        ->distinct('employee_id')
        ->count('employee_id');

      return view('admin.dashboard', compact(
        'totalEmployees', 'totalHours', 'totalEarnings', 'totalPickups', 'totalDrops',
        'onlineEmployees', 'awayEmployees',
        'todayHours', 'todayEarnings', 'todayPickups', 'todayDrops', 'todayEmployeesIn'
      ));
    }

  public function setting() {
    return view('admin.setting');
  }

  public function supportHelp() {
    return view('admin.support');
  }

  public function employeeReport(Request $request)
  {
    $from = $request->get('from') ? Carbon::parse($request->from) : Carbon::today();
    $to = $request->get('to') ? Carbon::parse($request->to) : Carbon::today();

    $employees = Employee::with([
      'attendances',
      'pickups',
      'drop',
      'markInLocation',
      'markOutLocation'
    ])->get();

    foreach ($employees as $emp) {
      // Filter attendances by date range
      $emp->filtered_attendances = $emp->attendances->filter(fn($att) =>
      Carbon::parse($att->mark_in)->between($from->startOfDay(), $to->endOfDay())
      );

      // Filter pickups/drops by date range
      $emp->filtered_pickups = $emp->pickups->filter(fn($p) =>
      Carbon::parse($p->created_at)->between($from->startOfDay(), $to->endOfDay())
      );

      // Calculate totals per employee
      $emp->total_hours = $emp->filtered_attendances->sum('hour');
      $emp->total_earnings = $emp->filtered_attendances->sum('earning');
      $emp->total_pickups = $emp->filtered_pickups->count();
      $emp->total_drops = $emp->filtered_pickups->filter(fn($p) => $p->drop)->count();
      $emp->mark_in_count = $emp->filtered_attendances->filter(fn($att) => $att->mark_in)->count();
      $emp->mark_out_count = $emp->filtered_attendances->filter(fn($att) => $att->mark_out)->count();
    }

    return view('admin.employee-report', compact('employees', 'from', 'to'));
  }


  public function attendanceReport(Request $request)
  {
    $view = $request->get('view', 'daily');

    $attendances = Attendance::with(['employee','employee.markInLocation','employee.markOutLocation'])
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

  public function employeeSummary(Request $request, $employeeId)
  {
    $from = $request->get('from') ? Carbon::parse($request->from) : Carbon::today();
    $to = $request->get('to') ? Carbon::parse($request->to) : Carbon::today();

    $employee = Employee::with([
      'attendances',
      'pickups',
      'drop',
      'markInLocation',
      'markOutLocation'
    ])->findOrFail($employeeId);

    // Filter attendances
    $filteredAttendances = $employee->attendances->filter(fn($att) =>
    Carbon::parse($att->mark_in)->between($from->startOfDay(), $to->endOfDay())
    );

    // Filter pickups/drops
    $filteredPickups = $employee->pickups->filter(fn($p) =>
    Carbon::parse($p->created_at)->between($from->startOfDay(), $to->endOfDay())
    );

    // Totals
    $summary = [
      'total_hours' => $filteredAttendances->sum('hour'),
      'total_earnings' => $filteredAttendances->sum('earning'),
      'total_pickups' => $filteredPickups->count(),
      'total_drops' => $filteredPickups->filter(fn($p) => $p->drop)->count(),
      'mark_in_count' => $filteredAttendances->filter(fn($att) => $att->mark_in)->count(),
      'mark_out_count' => $filteredAttendances->filter(fn($att) => $att->mark_out)->count(),
    ];

    return view('admin.employee-summary', compact('employee', 'summary', 'filteredAttendances', 'filteredPickups', 'from', 'to'));
  }


  public function updateTimezone(Request $request)
  {
    $request->validate([
      'timezone' => 'required|in:' . implode(',', timezone_identifiers_list()),
    ]);

    $timezone = $request->timezone;

    $envPath = base_path('.env');

    if (File::exists($envPath)) {
      $envContent = file_get_contents($envPath);

      // Replace APP_TIMEZONE if it exists
      if (strpos($envContent, 'APP_TIMEZONE=') !== false) {
        $envContent = preg_replace('/APP_TIMEZONE=.*/', 'APP_TIMEZONE=' . $timezone, $envContent);
      } else {
        // Append APP_TIMEZONE if not present
        $envContent .= "\nAPP_TIMEZONE={$timezone}";
      }

      file_put_contents($envPath, $envContent);
    }

    // Update runtime config immediately
    config(['app.timezone' => $timezone]);
    date_default_timezone_set($timezone);

    return back()->with('success', 'Application timezone updated to ' . $timezone);
  }


}
