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

    // Prevent duplicate mark-in without mark-out
    $existing = Attendance::where('employee_id', $employee->id)
      ->whereNull('mark_out')
      ->first();

    if ($existing) {
      return response()->json(['error' => 'Already marked in. Please mark out first.'], 400);
    }

    // Get assigned mark-in location (with alias and lat/lng)
    $employeeData = Employee::with('markInLocation')->find($employee->id);

    // Get user current coordinates from mobile device
    $userLat = floatval($request->latitude);
    $userLng = floatval($request->longitude);

    if ($employeeData && $employeeData->markInLocation) {

      $location = $employeeData->markInLocation;

      // Get assigned location coordinates
      $locLat = floatval($location->latitude);
      $locLng = floatval($location->longitude);

      // Calculate distance using Haversine formula
      $distanceKm = $this->haversineKm($userLat, $userLng, $locLat, $locLng);

      // Define radius (in km)
      $allowedRadiusKm = 0.1; // 100 meters

      if ($distanceKm > $allowedRadiusKm) {
        return response()->json([
          'error' => 'You are too far from the mark-in location (' . round($distanceKm * 1000) . ' meters away).',
        ], 400);
      }
    }

    // Proceed to mark in if within radius
    $attendance = Attendance::create([
      'employee_id' => $employee->id,
      'mark_in' => now(),
      'in_latitude' => $userLat,
      'in_longitude' => $userLng,
      'hourly_rate' => $employee->hourly_rate,
    ]);

    return response()->json([
      'message' => 'Marked in successfully at location: ' . $location->alias,
      'distanceKm' => round($distanceKm, 3),
      'attendance' => $attendance,
    ]);
  }

  /**
   * Calculate distance between two coordinates using Haversine formula.
   */
  private function haversineKm($lat1, $lon1, $lat2, $lon2)
  {
    $earthRadius = 6371; // km
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) ** 2 +
      cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
      sin($dLon / 2) ** 2;

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c;
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
