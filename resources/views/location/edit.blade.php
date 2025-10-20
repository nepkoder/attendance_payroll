@extends('layouts.contentNavbarLayout')

@section('title', 'Edit Location')

@section('content')

  <div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Edit Location</h5>
      <small class="text-body-secondary float-end">* Fields are required</small>
      <a href="{{ route('location.list') }}" class="btn btn-secondary btn-sm">Back to List</a>
    </div>

    <div class="card-body">
      <form action="{{ route('location.update', $location->id) }}" method="POST">
        @csrf
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Alias (Title) *</label>
              <input type="text" name="alias" value="{{ old('alias', $location->alias) }}" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Latitude *</label>
              <input type="text" name="latitude" value="{{ old('latitude', $location->latitude) }}" class="form-control" required>
            </div>
          </div>

          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Longitude *</label>
              <input type="text" name="longitude" value="{{ old('longitude', $location->longitude) }}" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Remarks</label>
              <textarea name="remarks" class="form-control" rows="4">{{ old('remarks', $location->remarks) }}</textarea>
            </div>
          </div>
        </div>

        <div class="mb-3 mt-4 float-end">
          <button type="submit" class="btn btn-primary">Update Location</button>
        </div>
      </form>
    </div>
  </div>

@endsection
