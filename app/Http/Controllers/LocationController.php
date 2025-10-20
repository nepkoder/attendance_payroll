<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\VehicleDrop;
use App\Models\VehiclePickup;
use Illuminate\Http\Request;

class LocationController extends Controller
{

  // List all locations
  public function index()
  {
    $locations = Location::orderBy('id', 'desc')->get();
    return view('location.list', compact('locations'));
  }

  // Show add form
  public function create()
  {
    return view('location.add');
  }

  // Store new location
  public function store(Request $request)
  {
    $request->validate([
      'alias' => 'required|string|max:255',
      'latitude' => 'required|numeric',
      'longitude' => 'required|numeric',
      'remarks' => 'nullable|string',
    ]);

    Location::create([
      'alias' => $request->alias,
      'latitude' => $request->latitude,
      'longitude' => $request->longitude,
      'remarks' => $request->remarks,
    ]);

    return redirect()->route('location.list')->with('success', 'Location added successfully!');
  }

  // Show edit form
  public function edit($id)
  {
    $location = Location::findOrFail($id);
    return view('location.edit', compact('location'));
  }

  // Update location
  public function update(Request $request, $id)
  {
    $request->validate([
      'alias' => 'required|string|max:255',
      'latitude' => 'required|numeric',
      'longitude' => 'required|numeric',
      'remarks' => 'nullable|string',
    ]);

    $location = Location::findOrFail($id);
    $location->update([
      'alias' => $request->alias,
      'latitude' => $request->latitude,
      'longitude' => $request->longitude,
      'remarks' => $request->remarks,
    ]);

    return redirect()->route('location.list')->with('success', 'Location updated successfully!');
  }

  // Delete location
  public function destroy($id)
  {
    $location = Location::findOrFail($id);
    $location->delete();

    return redirect()->route('location.list')->with('success', 'Location deleted successfully!');
  }

}
