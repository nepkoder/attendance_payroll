<?php


namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Location;
use App\Models\VehiclePickup;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{

  public function dashboard()
  {
    // Get logged-in employee
    $employee = Auth::guard('employee')->user();

    // Get all attendances of this employee
    $attendances = Attendance::where('employee_id', $employee->id)->get();

    // Total seconds & earnings
    $totalSeconds = $attendances->sum(function ($attendance) {
      if ($attendance->mark_in && $attendance->mark_out) {
        return Carbon::parse($attendance->mark_in)->diffInSeconds(Carbon::parse($attendance->mark_out));
      }
      return 0;
    });
    $totalEarnings = $attendances->sum('earning');

    // Total time in days, hours, minutes, seconds
    $totalTime = [
      'days' => floor($totalSeconds / 86400),
      'hours' => floor(($totalSeconds % 86400) / 3600),
      'minutes' => floor(($totalSeconds % 3600) / 60),
      'seconds' => $totalSeconds % 60,
    ];

    $totalHoursDecimal = $totalSeconds / 3600; // converts total seconds to hours
    $totalHoursDecimal = round($totalHoursDecimal, 2); // round to 2 decimals


    // Today's earning
    $today = Carbon::today();
    $todayEarnings = $attendances->where('mark_in', '>=', $today)->sum('earning');

    // Latest session (running or completed)
    $latestSession = $attendances->sortByDesc('mark_in')->first();

    if ($latestSession) {
//      $markIn = Carbon::parse($latestSession->mark_in);
//      $markOut = $latestSession->mark_out ? Carbon::parse($latestSession->mark_out) : Carbon::now();

//      $sessionSeconds = $markOut->diffInSeconds($markIn);

//      $sessionTime = [
//        'days' => floor($sessionSeconds / 86400),
//        'hours' => floor(($sessionSeconds % 86400) / 3600),
//        'minutes' => floor(($sessionSeconds % 3600) / 60),
//        'seconds' => $sessionSeconds % 60,
//      ];

      $markinTime = $latestSession->mark_in;
      $markoutTime = $latestSession->mark_out;
      $sessionStatus = $latestSession->mark_out ? 'completed' : 'running';
    } else {
//      $sessionTime = ['days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 0];
      $sessionStatus = 'no session';
      $markinTime = null;
      $markoutTime = null;
    }

    // Total pickups
    $totalPickups = VehiclePickup::count();

    // Total drops
    $totalDrops = VehiclePickup::whereHas('drop')->count();

    // Today's pickups
    $todaysPickups = VehiclePickup::whereDate('created_at', $today)->count();

    // Today's drops
    $todaysDrops = VehiclePickup::whereHas('drop', function ($q) use ($today) {
      $q->whereDate('created_at', $today);
    })->count();

    return view('employee.dashboard', compact(
      'employee',
      'totalEarnings',
      'totalTime',
      'todayEarnings',
//      'sessionTime',
      'markinTime',
      'totalHoursDecimal',
      'markoutTime',
      'sessionStatus',
      'totalPickups',
      'totalDrops',
      'todaysPickups',
      'todaysDrops'
    ));
  }

  public function pickup()
  {
    $pickups = VehiclePickup::with('drop')->latest()->get();
    $pendingPickups = VehiclePickup::doesntHave('drop')->get(); // ✅ only pickups without drop

    return view('employee.pickup', compact('pickups', 'pendingPickups'));
  }

  public function drop()
  {
    $pickups = VehiclePickup::with('drop')->latest()->get();
    $pendingPickups = VehiclePickup::doesntHave('drop')->get(); // ✅ only pickups without drop
    return view('employee.drop', compact('pickups', 'pendingPickups'));
  }

  public function attendanceReport(Request $request)
  {
    $userId = Auth::guard('employee')->id();

    // Get from/to dates from request or default to last 7 days
    $from = $request->query('from') ? Carbon::parse($request->query('from'))->startOfDay() : Carbon::now()->subDays(7)->startOfDay();
    $to = $request->query('to') ? Carbon::parse($request->query('to'))->endOfDay() : Carbon::now()->endOfDay();

    // Fetch attendances within range
    $attendances = Attendance::where('employee_id', $userId)
      ->whereBetween('mark_in', [$from, $to])
      ->orderBy('mark_in')
      ->get();

    // Group attendances by date
    $dailyAttendances = $attendances->groupBy(function ($a) {
      return $a->mark_in->format('Y-m-d');
    });

    $report = [];

    foreach ($dailyAttendances as $date => $items) {
      $totalHours = $items->sum('hour');
      $totalEarnings = $items->sum('earning');
      $firstIn = $items->min('mark_in')?->format('H:i') ?? '-';
      $lastOut = $items->max('mark_out')?->format('H:i') ?? '-';

      $report[] = [
        'label' => $date,
        'in' => $firstIn,
        'out' => $lastOut,
        'hours' => $totalHours,
        'earnings' => $totalEarnings,
        'deductions' => 0,
        'days' => 1,
      ];
    }

    // Totals
    $totalHours = array_sum(array_column($report, 'hours'));
    $totalEarnings = array_sum(array_column($report, 'earnings'));
    $totalDays = array_sum(array_column($report, 'days'));

    return view('employee.attendance', compact('report', 'totalHours', 'totalEarnings', 'totalDays', 'from', 'to'));
  }
  public function pdReport(Request $request)
  {
    // Default last 7 days
    $from = $request->query('from')
      ? Carbon::parse($request->query('from'))->startOfDay()
      : Carbon::now()->subDays(6)->startOfDay();
    $to = $request->query('to')
      ? Carbon::parse($request->query('to'))->endOfDay()
      : Carbon::now()->endOfDay();

    $query = VehiclePickup::with('drop');

    $query->whereBetween('created_at', [$from, $to]);

    $pickups = $query->latest()->get();

    // Summary
    $summary = [
      'total_pickups' => $pickups->count(),
      'total_drops' => $pickups->where('drop')->count(),
    ];

    return view('employee.pdreport', compact('pickups', 'summary', 'from', 'to'));
  }
  public function earningsReport(Request $request)
  {
    $employeeId = Auth::guard('employee')->id();

    // Default last 7 days
    $fromDate = $request->query('from')
      ? Carbon::parse($request->query('from'))->startOfDay()
      : Carbon::now()->subDays(6)->startOfDay();
    $toDate = $request->query('to')
      ? Carbon::parse($request->query('to'))->endOfDay()
      : Carbon::now()->endOfDay();

    // Get attendances for the employee in the date range
    $attendances = Attendance::where('employee_id', $employeeId)
      ->whereBetween('mark_in', [$fromDate, $toDate])
      ->orderBy('mark_in', 'asc')
      ->get();

    // Group attendances by date
    $records = $attendances->groupBy(function ($item) {
      return $item->mark_in->format('Y-m-d');
    })->map(function ($dayAttendances, $date) {
      $totalHours = $dayAttendances->sum('hour');
      $totalEarnings = $dayAttendances->sum('earning');
      $totalDeductions = $dayAttendances->sum(function ($a) {
        return ($a->hour * $a->hourly_rate) - $a->earning;
      });

      return [
        'period' => $date,
        'total_hours' => $totalHours,
        'total_earnings' => $totalEarnings,
        'total_deductions' => $totalDeductions,
      ];
    });

    return view('employee.earning', compact('records', 'fromDate', 'toDate'));
  }



  public function profileEdit()
  {
    $employee = Auth::guard('employee')->user();
    return view('employee.profile', compact('employee'));
  }

  public function updateProfile(Request $request)
  {
    $employee = Auth::user();

    $request->validate([
      'name' => 'required|string|max:255',
//      'username' => 'required|string|max:255|unique:employees,username,' . $employee->id,
//      'email' => 'required|email|max:255|unique:employees,email,' . $employee->id,
      'phone' => 'nullable|string|max:20',
      'company' => 'nullable|string|max:255',
      'department' => 'nullable|string|max:255',
      'address' => 'nullable|string|max:500',
      'remarks' => 'nullable|string',
      'image' => 'nullable|image|max:2048',
      'document_image' => 'nullable|image|max:2048',
      'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
    ]);

    $employee->name = $request->name;
//    $employee->username = $request->username;
//    $employee->email = $request->email;
    $employee->phone = $request->phone;
    $employee->company = $request->company;
    $employee->department = $request->department;
    $employee->address = $request->address;
    $employee->remarks = $request->remarks;

    if ($request->hasFile('image')) {
      if ($employee->image) {
        Storage::delete($employee->image);
      }
      $employee->image = $request->file('image')->store('employees/profile');
    }

    if ($request->hasFile('document_image')) {
      if ($employee->document_image) {
        Storage::delete($employee->document_image);
      }
      $employee->document_image = $request->file('document_image')->store('employees/documents');
    }

    // Upload multiple additional documents
    if ($request->hasFile('documents')) {
      $files = [];
      foreach ($request->file('documents') as $file) {
        $files[] = $file->store('employees/documents');
      }
      // store as JSON array
      $employee->documents = $files;
    }

    $employee->save();

    return back()->with('success', 'Profile updated successfully!');
  }

  public function changePassword(Request $request)
  {
    $request->validate([
      'current_password' => 'required',
      'new_password' => 'required|min:3|confirmed',
    ]);

    $employee = Auth::guard('employee')->user();

    if (!Hash::check($request->current_password, $employee->password)) {
      return back()->withErrors(['current_password' => 'Current password is incorrect']);
    }

    $employee->password = Hash::make($request->new_password);
    $employee->save();

    return back()->with('success', 'Password changed successfully!');
  }


  // Employee Auth
  public function showLoginForm()
  {
    return view('employee.login');
  }
  public function login(Request $request)
  {
    $request->validate([
      'username' => 'required|string',
      'password' => 'required|string',
    ]);

    $credentials = $request->only('username', 'password');

    if (Auth::guard('employee')->attempt($credentials, $request->filled('remember'))) {
      return redirect()->intended(route('employee.dashboard'));
    }

    return back()->withErrors([
      'username' => 'Invalid username or password.',
    ])->onlyInput('username');
  }


  public function logout(Request $request)
  {
    Auth::guard('employee')->logout();
//    $request->session()->invalidate();
//    $request->session()->regenerateToken();
    return redirect()->route('employee.login');
  }

  // EMployee Auth End

  public function index()
  {
    $employees = Employee::with(['markInLocation', 'markOutLocation'])->latest()->get();
    return view('employee.index', compact('employees'));
  }

  public function markIn(Request $request)
  {

  }

  public function create()
  {
    $locations = Location::all();
    return view('employee.create', compact('locations'));
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:employees,email',
      'username' => 'required|string|unique:employees,username',
      'password' => 'required|string|min:4',
      'status' => 'required|in:active,inactive',
      'image' => 'nullable|image|mimes:jpg,png,jpeg',
      'document_image' => 'nullable|file|mimes:pdf,jpg,png,jpeg',
      'mark_in_location_id' => 'nullable|exists:locations,id',
      'mark_out_location_id' => 'nullable|exists:locations,id'
    ]);

    $data = $request->except(['image', 'document_image']);
    $data['password'] = Hash::make($request->password);

    // handle image upload
    if ($request->hasFile('image')) {
      $data['image'] = $request->file('image')->store('employees', 'public');
    }

    if ($request->hasFile('document_image')) {
      $data['document_image'] = $request->file('document_image')->store('employee_docs', 'public');
    }

    Employee::create($data);

    return redirect()->route('employee.index')->with('success', 'Employee created successfully.');
  }

  public function show($id)
  {
    $employee = Employee::with(['markInLocation', 'markOutLocation'])->findOrFail($id);
    return view('employee.view', compact('employee'));
  }


  public function edit($id)
  {
    $employee = Employee::findOrFail($id);
    $locations = Location::all();
    return view('employee.edit', compact('employee', 'locations'));
  }

  public function update(Request $request, $id)
  {
    $employee = Employee::findOrFail($id);

    $data = $request->validate([
      'name' => 'required|string|max:255',
      'email' => "required|email|unique:employees,email,$id",
      'username' => "required|string|unique:employees,username,$id",
      'password' => 'nullable|string|min:6',
      'status' => 'required|in:active,inactive',
      'image' => 'nullable|image|mimes:jpg,png,jpeg',
      'document_image' => 'nullable|file|mimes:pdf,jpg,png,jpeg',
      'mark_in_location_id' => 'nullable|exists:locations,id',
      'mark_out_location_id' => 'nullable|exists:locations,id',
      'hourly_rate' => 'nullable'
    ]);

    $data = $request->except(['image', 'document_image', 'password']);
    if ($request->filled('password')) {
      $data['password'] = Hash::make($request->password);
    }

    if ($request->hasFile('image')) {
      $data['image'] = $request->file('image')->store('employees', 'public');
    }

    if ($request->hasFile('document_image')) {
      $data['document_image'] = $request->file('document_image')->store('employee_docs', 'public');
    }

    $employee->update($data);

    return redirect()->route('employee.index')->with('success', 'Employee updated successfully.');
  }

  public function destroy($id)
  {
    $employee = Employee::findOrFail($id);
    $employee->delete();

    return redirect()->route('employee.index')->with('success', 'Employee deleted successfully.');
  }

  public function hourlyRateList()
  {
    $employees = Employee::select('id', 'name', 'email', 'hourly_rate', 'image', 'status')->get();
    return view('admin.hourly_rate', compact('employees'));
  }

  public function updateHourlyRate(Request $request)
  {
    $request->validate([
      'employee_id' => 'required|exists:employees,id',
      'hourly_rate' => 'required|numeric|min:0',
    ]);

    $employee = Employee::findOrFail($request->employee_id);
    $employee->hourly_rate = $request->hourly_rate;
    $employee->save();

    return redirect()->route('employee.hourlyRateList')->with('success', 'Hourly rate updated successfully!');
  }


}
