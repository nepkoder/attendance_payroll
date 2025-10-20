@extends('layouts/contentNavbarLayout')

@section('title', 'Location List')

@section('content')

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Location List</h5>
      <a href="{{ route('location.add') }}" class="btn btn-primary btn-sm">+ Add Location</a>
    </div>    <div class="card-body">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark text-center">
        <tr>
          <th style="width:50px;">#</th>
          <th>Alias (Title)</th>
          <th>Latitude</th>
          <th>Longitude</th>
          <th>Remarks</th>
          <th style="width:200px;">Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($locations as $index => $location)
          <tr>
            <td class="text-center fw-bold">{{ $index + 1 }}</td>
            <td>{{ $location->alias }}</td>
            <td>{{ $location->latitude }}</td>
            <td>{{ $location->longitude }}</td>
            <td>{{ $location->remarks ?? '-' }}</td>
            <td class="text-center">
              <a href="{{ route('location.edit', $location->id) }}" class="btn btn-sm btn-info me-1">
                <i class="bx bx-edit"></i> Edit
              </a>
              <form action="{{ route('location.delete', $location->id) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this location?')">
                  <i class="bx bx-trash"></i> Delete
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center py-5 text-muted">
              <img src="https://cdn-icons-png.flaticon.com/512/7486/7486797.png" alt="No Data" width="80" class="mb-3 opacity-75"><br>
              <h6>No locations found</h6>
              <small>Click “Add Location” to create a new one.</small><br>
              <a href="{{ route('location.add') }}" class="btn btn-primary btn-sm mt-2">
                + Add New Location
              </a>
            </td>
          </tr>
        @endforelse
        </tbody>
      </table>    </div>
  </div>

@endsection
