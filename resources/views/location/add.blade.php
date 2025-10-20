@extends('layouts.contentNavbarLayout')

@section('title', 'Add Location')

@section('content')

  <div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Add New Location</h5>
      <small class="text-body-secondary float-end">* Fields are required</small>
      <a href="{{ route('location.list') }}" class="btn btn-secondary btn-sm">Back to List</a>
    </div>

    <div class="card-body">
      <form action="{{ route('location.store') }}" method="POST">
        @csrf

        <div class="row">
          <div class="col-md-6">
            <!-- Alias -->
            <div class="mb-3">
              <label for="alias" class="form-label">Alias (Title) *</label>
              <input
                type="text"
                name="alias"
                id="alias"
                class="form-control"
                placeholder="Type Location or Alias"
                required
              >
            </div>

            <!-- Get Current Location Button -->
            <div class="mb-3">
              <button type="button" id="getLocationBtn" class="btn btn-outline-primary w-100">
                📍 Get Current Location
              </button>
              <small id="locationMessage" class="d-block mt-2 text-muted"></small>
            </div>

            <!-- Latitude -->
            <div class="mb-3">
              <label for="latitude" class="form-label">Latitude *</label>
              <input
                type="text"
                name="latitude"
                id="latitude"
                class="form-control"
                placeholder="27.7172"
                required
              >
            </div>

            <!-- Longitude -->
            <div class="mb-3">
              <label for="longitude" class="form-label">Longitude *</label>
              <input
                type="text"
                name="longitude"
                id="longitude"
                class="form-control"
                placeholder="85.3240"
                required
              >
            </div>
          </div>

          <div class="col-md-6">
            <!-- Remarks -->
            <div class="mb-3">
              <label for="remarks" class="form-label">Remarks</label>
              <textarea
                name="remarks"
                id="remarks"
                class="form-control"
                rows="4"
                placeholder="Enter additional notes or location details..."
              ></textarea>
            </div>
          </div>
        </div>

        <div class="mt-4 float-end">
          <button type="submit" class="btn btn-primary">Save Location</button>
        </div>
      </form>
    </div>
  </div>

@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const getLocationBtn = document.getElementById('getLocationBtn');
      const latitudeInput = document.getElementById('latitude');
      const longitudeInput = document.getElementById('longitude');
      const messageEl = document.getElementById('locationMessage');

      getLocationBtn.addEventListener('click', () => {
        messageEl.textContent = "📡 Fetching your current location...";
        messageEl.classList.remove('text-danger', 'text-success');
        messageEl.classList.add('text-muted');

        if (!navigator.geolocation) {
          messageEl.textContent = "❌ Geolocation is not supported by your browser.";
          messageEl.classList.remove('text-muted');
          messageEl.classList.add('text-danger');
          return;
        }

        navigator.geolocation.getCurrentPosition(
          (position) => {
            const lat = position.coords.latitude.toFixed(6);
            const lng = position.coords.longitude.toFixed(6);

            latitudeInput.value = lat;
            longitudeInput.value = lng;

            messageEl.textContent = "✅ Location detected successfully!";
            messageEl.classList.remove('text-muted');
            messageEl.classList.add('text-success');
          },
          (error) => {
            let errorMsg = "❌ Unable to retrieve your location.";
            switch (error.code) {
              case error.PERMISSION_DENIED:
                errorMsg = "❌ Location permission denied. Please allow access.";
                break;
              case error.POSITION_UNAVAILABLE:
                errorMsg = "⚠️ Location information unavailable.";
                break;
              case error.TIMEOUT:
                errorMsg = "⏳ Location request timed out. Try again.";
                break;
            }
            messageEl.textContent = errorMsg;
            messageEl.classList.remove('text-muted', 'text-success');
            messageEl.classList.add('text-danger');
          }
        );
      });
    });
  </script>
@endpush
