<?php

namespace App\Http\Controllers;

use App\Models\VehicleDrop;
use App\Models\VehiclePickup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class VehicleEntryController extends Controller
{
  // Show Pickup + Drop forms
  public function index()
  {
    $pickups = VehiclePickup::with('drop')->latest()->get();
    $pendingPickups = VehiclePickup::doesntHave('drop')->get(); // ✅ only pickups without drop

    return view('vehicle-entry', compact('pickups', 'pendingPickups'));
  }

  // Store Pickup Entry
  public function storePickup(Request $request)
  {
    $request->validate([
      'vehicle_number' => 'required|string|max:50',
      'camera_image' => 'nullable|string', // Base64 from hidden input
      'images.*' => 'nullable|image',
      'remarks' => 'nullable|string',
    ]);

    $cameraPath = null;
    if ($request->camera_image) {
      // Decode base64 and store
      $cameraData = $request->camera_image;
      $cameraData = preg_replace('/^data:image\/\w+;base64,/', '', $cameraData);
      $cameraData = str_replace(' ', '+', $cameraData);
      $cameraPath = 'pickups/' . Str::random(10) . '.jpg';
      Storage::disk('public')->put($cameraPath, base64_decode($cameraData));
    }

    $imagePaths = [];
    if ($request->hasFile('images')) {
      foreach ($request->file('images') as $image) {
        $imagePaths[] = $image->store('pickups', 'public');
      }
    }

    VehiclePickup::create([
      'vehicle_number' => $request->vehicle_number,
      'camera_image' => $cameraPath,
      'images' => $imagePaths,
      'employee_id' => Auth::id(),
      'remarks' => $request->remarks,
    ]);

    return redirect()->back()->with('success', 'Pickup entry saved successfully!');
  }

  // Store Drop Entry
  public function storeDrop(Request $request)
  {
    $request->validate([
      'pickup_id' => 'required|exists:vehicle_pickups,id',
      'camera_image' => 'nullable|string', // Base64 from hidden input
      'images.*' => 'nullable|image',
      'remarks' => 'nullable|string',
    ]);

    // Prevent duplicate drop entry for same pickup
    if (VehicleDrop::where('pickup_id', $request->pickup_id)->exists()) {
      return redirect()->back()->with('error', 'Drop entry already exists for this vehicle!');
    }

    $cameraPath = null;
    if ($request->camera_image) {
      $cameraData = $request->camera_image;
      $cameraData = preg_replace('/^data:image\/\w+;base64,/', '', $cameraData);
      $cameraData = str_replace(' ', '+', $cameraData);
      $cameraPath = 'drops/' . Str::random(10) . '.jpg';
      Storage::disk('public')->put($cameraPath, base64_decode($cameraData));
    }

    $imagePaths = [];
    if ($request->hasFile('images')) {
      foreach ($request->file('images') as $image) {
        $imagePaths[] = $image->store('drops', 'public');
      }
    }

    VehicleDrop::create([
      'pickup_id' => $request->pickup_id,
      'camera_image' => $cameraPath,
      'images' => $imagePaths,
      'remarks' => $request->remarks,
      'employee_id' => Auth::id(),
    ]);

    return redirect()->back()->with('success', 'Drop entry saved successfully!');
  }


  // MOBILE API
  public function storePickupMobile(Request $request)
  {
    $request->validate([
      'vehicle_number' => 'required|string|max:50',
      'camera_image' => 'nullable|string', // Base64 from hidden input
      'images.*' => 'nullable|image',
      'remarks' => 'nullable|string',
    ]);

    $cameraPath = null;
    if ($request->camera_image) {
      // Decode base64 and store
      $cameraData = $request->camera_image;
      $cameraData = preg_replace('/^data:image\/\w+;base64,/', '', $cameraData);
      $cameraData = str_replace(' ', '+', $cameraData);
      $cameraPath = 'pickups/' . Str::random(10) . '.jpg';
      Storage::disk('public')->put($cameraPath, base64_decode($cameraData));
    }

    $imagePaths = [];
    if ($request->hasFile('images')) {
      foreach ($request->file('images') as $image) {
        $imagePaths[] = $image->store('pickups', 'public');
      }
    }

    VehiclePickup::create([
      'vehicle_number' => $request->vehicle_number,
      'camera_image' => $cameraPath,
      'images' => $imagePaths,
      'employee_id' => Auth::id(),
      'remarks' => $request->remarks,
    ]);

    return response()->json(['status' => 'success', 'message' => 'Pickup entry saved successfully!']);
  }

  // Store Drop Entry
  public function storeDropMobile(Request $request)
  {
    $request->validate([
      'pickup_id' => 'required|exists:vehicle_pickups,id',
      'camera_image' => 'nullable|string', // Base64 from hidden input
      'images.*' => 'nullable|image',
      'remarks' => 'nullable|string',
    ]);

    // Prevent duplicate drop entry for same pickup
    if (VehicleDrop::where('pickup_id', $request->pickup_id)->exists()) {
      return response()->json(['status' => 'error', 'message' => 'Drop entry already exists for this vehicle.'],400);
    }

    $cameraPath = null;
    if ($request->camera_image) {
      $cameraData = $request->camera_image;
      $cameraData = preg_replace('/^data:image\/\w+;base64,/', '', $cameraData);
      $cameraData = str_replace(' ', '+', $cameraData);
      $cameraPath = 'drops/' . Str::random(10) . '.jpg';
      Storage::disk('public')->put($cameraPath, base64_decode($cameraData));
    }

    $imagePaths = [];
    if ($request->hasFile('images')) {
      foreach ($request->file('images') as $image) {
        $imagePaths[] = $image->store('drops', 'public');
      }
    }

    VehicleDrop::create([
      'pickup_id' => $request->pickup_id,
      'camera_image' => $cameraPath,
      'images' => $imagePaths,
      'remarks' => $request->remarks,
      'employee_id' => Auth::id(),
    ]);

    return response()->json(['status' => 'success', 'message' => 'Drop entry saved successfully!']);
  }


}
