<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
  public function markIn(Request $request)
  {

    $employee = Auth::guard('employee')->user();

    if ($employee->hourly_rate <= 0) {
      return response()->json(['error' => 'No Rate Found. Please update the hourly rate to continue.'], 400);
    }

    // Prevent duplicate mark-in without mark-out
    $existing = Attendance::where('employee_id', $employee->id)
      ->whereNull('mark_out')
      ->first();

    if ($existing) {
      return response()->json(['error' => 'Already marked in. Please mark out first.'], 400);
    }

    $attendance = Attendance::create([
      'employee_id' => $employee->id,
      'mark_in' => now(),
      'in_latitude' => $request->latitude,
      'in_longitude' => $request->longitude,
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

    $attendance->mark_out = now();
    $attendance->out_latitude = $request->latitude;
    $attendance->out_longitude = $request->longitude;

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
