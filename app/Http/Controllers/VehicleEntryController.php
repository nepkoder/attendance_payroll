<?php

namespace App\Http\Controllers;

use App\Models\VehicleDrop;
use App\Models\VehiclePickup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class VehicleEntryController extends Controller
{

  public function pickupList() {
    $pickups = VehiclePickup::whereDoesntHave('drop') // Only pickups with no drop
    ->select('id', 'vehicle_number', 'created_at')
      ->orderByDesc('created_at')
      ->get();

    $totalPickups = VehiclePickup::count();        // All pickups
    $totalDrops   = VehicleDrop::count();          // All drops
    $pendingPickups = VehiclePickup::whereDoesntHave('drop')->count(); // Pickups not dropped yet

    return response()->json([
      'total_pickups' => $totalPickups,
      'total_drops' => $totalDrops,
      'pending_pickups' => $pendingPickups,
      'pickups' => $pickups
    ]);
  }

  // Show Pickup + Drop forms
  public function index()
  {
    $pickups = VehiclePickup::with('drop')->latest()->get();
    $pendingPickups = VehiclePickup::doesntHave('drop')->get(); // ✅ only pickups without drop

    return view('vehicle-entry', compact('pickups', 'pendingPickups'));
  }
  public function storePickup(Request $request)
  {
    // 1️⃣ Validate request
    $request->validate([
      'vehicle_number' => 'required|string|max:50',
      'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
      'camera_images.*' => 'nullable|string',
      'remarks' => 'nullable|string|max:500',
    ]);

    $allImagePaths = [];

    // 2️⃣ Handle file uploads
    if ($request->hasFile('images')) {
      foreach ($request->file('images') as $file) {
        $path = $file->store('pickups', 'public'); // stores in storage/app/public/pickups
        $allImagePaths[] = $path;
      }
    }

    // 3️⃣ Handle camera captures (Base64)
    if ($request->camera_images) {
      foreach ($request->camera_images as $base64Image) {
        // Remove base64 header
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
        $imageData = str_replace(' ', '+', $imageData);

        $filename = 'pickups/' . Str::random(12) . '.jpg';
        Storage::disk('public')->put($filename, base64_decode($imageData));

        $allImagePaths[] = $filename;
      }
    }

    // 4️⃣ Save in database
    VehiclePickup::create([
      'vehicle_number' => $request->vehicle_number,
      'images' => $allImagePaths, // JSON column
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
      'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
      'camera_images.*' => 'nullable|string',
      'remarks' => 'nullable|string|max:500',
    ]);

    $allImagePaths = [];

    // Handle file uploads
    if ($request->hasFile('images')) {
      foreach ($request->file('images') as $file) {
        $allImagePaths[] = $file->store('drops', 'public');
      }
    }

    // Handle camera images (Base64)
    if ($request->camera_images) {
      foreach ($request->camera_images as $cameraImage) {
        $cameraData = preg_replace('/^data:image\/\w+;base64,/', '', $cameraImage);
        $cameraData = str_replace(' ', '+', $cameraData);
        $cameraPath = 'drops/' . Str::random(10) . '.jpg';
        Storage::disk('public')->put($cameraPath, base64_decode($cameraData));
        $allImagePaths[] = $cameraPath;
      }
    }

    VehicleDrop::create([
      'pickup_id' => $request->pickup_id,
      'images' => $allImagePaths,
      'employee_id' => Auth::id(),
      'remarks' => $request->remarks,
    ]);

    return redirect()->back()->with('success', 'Drop entry saved successfully!');
  }

  // MOBILE API
  public function storePickupMobile(Request $request)
  {
    $request->validate([
      'vehicle_number' => 'required|string|max:50',
      'remarks' => 'nullable|string|max:500',
//      'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
    ]);

//    $allImagePaths = [];
//
//    // Handle uploaded images
//    if ($request->hasFile('images')) {
//      foreach ($request->file('images') as $file) {
//        if ($file->isValid()) {
//          $path = $file->store('pickups', 'public');
//          $allImagePaths[] = $path;
//        }
//      }
//    }

    // Save to database
    $pickup = VehiclePickup::create([
      'vehicle_number' => strtoupper(trim($request->vehicle_number)),
      'images' => $request->images,
      'employee_id' => $request->employee_id ?? null,
      'remarks' => $request->remarks,
    ]);

    return response()->json([
      'status' => 'success',
      'message' => 'Pickup entry saved successfully!',
      'data' => $pickup,
    ], 200);
  }

  // Store Drop Entry
  public function storeDropMobile(Request $request)
  {
    $request->validate([
      'pickup_id' => 'required|exists:vehicle_pickups,id',
//      'images.*' => 'nullable|image',
      'remarks' => 'nullable|string',
    ]);

    Log::info("Req Data",['data' => $request->all()]);

    // Prevent duplicate drop entry for same pickup
    if (VehicleDrop::where('pickup_id', $request->pickup_id)->exists()) {
      return response()->json(['status' => 'error', 'message' => 'Drop entry already exists for this vehicle.'],400);
    }
//
//    $allImagePaths = [];
//
//    // Handle uploaded images
//    if ($request->hasFile('images')) {
//      foreach ($request->file('images') as $file) {
//        if ($file->isValid()) {
//          $path = $file->store('pickups', 'public');
//          $allImagePaths[] = $path;
//        }
//      }
//    }

    VehicleDrop::create([
      'pickup_id' => $request->pickup_id,
      'images' => $request->images,
      'remarks' => $request->remarks,
      'employee_id' => $request->employee_id ?? null,
    ]);

    return response()->json(['status' => 'success', 'message' => 'Drop entry saved successfully!']);
  }

  public function uploadImage(Request $request) {

    if ($request->hasFile('image')) {
      $file = $request->file('image');

        if ($file->isValid()) {

          $type = $request->type;
          $storeFolder= 'upload';

          if ($type == 'PICKUP')
            $storeFolder = 'pickups';
          if($type == 'DROPOFF')
            $storeFolder = 'dropoff';

          $company = $request->header('X-Company') ?? '';

          // ✅ Generate a custom filename
          $extension = $file->getClientOriginalExtension();
          $filename = $company . '_' . time() . '.' . $extension;

          // ✅ Store file with custom name
          $path = $file->storeAs($storeFolder, $filename, 'public');

          return response()->json(['status' => 'success', 'message' => 'Image uploaded successfully!', 'data'=> $path]);
        }
    }

  }


}
