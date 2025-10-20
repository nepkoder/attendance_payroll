<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
  public function markIn(Request $request)
  {

    $employee = Auth::guard('employee')->user();

//    if ($employee->hourly_rate <= 0) {
//      return response()->json(['error' => 'No Rate Found. Please update the hourly rate to continue.'], 400);
//    }

    // Prevent duplicate mark-in without mark-out
    $existing = Attendance::where('employee_id', $employee->id)
      ->whereNull('mark_out')
      ->first();

    if ($existing) {
      return response()->json(['error' => 'Already marked in. Please mark out first.'], 400);
    }

    $location = Employee::with('markInLocation')->find($employee->id);

    $inLat = $request->latitude;
    $inLng = $request->longitude;

    if ($location && $location->markInLocation) {
      $inLat = $location->markInLocation->latitude;
      $inLng = $location->markInLocation->longitude;
    }

    $attendance = Attendance::create([
      'employee_id' => $employee->id,
      'mark_in' => now(),
      'in_latitude' => $inLat ?? 0,
      'in_longitude' => $inLng ?? 0,
      'hourly_rate' => $employee->hourly_rate
    ]);

    return response()->json([
      'message' => 'Marked in successfully.',
      'attendance' => $attendance,
    ]);
  }

  public function markOut(Request $request)
  {
    $employee = Auth::guard('employee')->user();

    $attendance = Attendance::where('employee_id', $employee->id)
      ->whereNull('mark_out')
      ->latest('id')
      ->first();

    if (!$attendance) {
      return response()->json(['error' => 'No active session found. Please mark in first.'], 400);
    }


    $location = Employee::with('markOutLocation')->find($employee->id);

    $outLat = $request->latitude ?? 0;
    $outLng = $request->longitude ?? 0;

    if ($location && $location->markOutLocation) {
      $outLat = $location->markOutLocation->latitude;
      $outLng = $location->markOutLocation->longitude;
    }

    $attendance->mark_out = now();
    $attendance->out_latitude = $outLat ?? 0;
    $attendance->out_longitude = $outLng ?? 0;

    // Calculate worked hours & earnings
    $hours = Carbon::parse($attendance->mark_in)->diffInMinutes($attendance->mark_out) / 60;
    $attendance->hour = number_format($hours, 2);
    $attendance->earning = $hours * $employee->hourly_rate; // example: £100/hour
    $attendance->save();

    return response()->json([
      'message' => 'Marked out successfully.',
      'attendance' => $attendance,
    ]);
  }

}
