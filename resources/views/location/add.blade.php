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
                <span id="btnIcon">📍</span> <span id="btnText">Get Current Location</span>
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
    document.addEventListener('DOMContentLoaded', function() {
      const getLocationBtn = document.getElementById('getLocationBtn');
      const latitudeInput = document.getElementById('latitude');
      const longitudeInput = document.getElementById('longitude');
      const messageEl = document.getElementById('locationMessage');
      const btnIcon = document.getElementById('btnIcon');
      const btnText = document.getElementById('btnText');

      getLocationBtn.addEventListener('click', function() {
        // Check if browser supports geolocation
        if (!navigator.geolocation) {
          showMessage("❌ Geolocation is not supported by your browser.", 'danger');
          return;
        }

        // Check if page is served over HTTPS (required for geolocation in most browsers)
        if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
          showMessage("⚠️ Geolocation requires HTTPS connection. Please use secure connection.", 'warning');
          return;
        }

        // Disable button and show loading state
        getLocationBtn.disabled = true;
        btnIcon.textContent = '⏳';
        btnText.textContent = 'Getting Location...';
        showMessage("📡 Fetching your current location...", 'muted');

        // Options for better accuracy
        const options = {
          enableHighAccuracy: true,
          timeout: 10000,
          maximumAge: 0
        };

        navigator.geolocation.getCurrentPosition(
          function(position) {
            // Success callback
            const lat = position.coords.latitude.toFixed(6);
            const lng = position.coords.longitude.toFixed(6);

            latitudeInput.value = lat;
            longitudeInput.value = lng;

            showMessage(`✅ Location detected successfully! (Accuracy: ${Math.round(position.coords.accuracy)}m)`, 'success');

            // Reset button
            btnIcon.textContent = '✅';
            btnText.textContent = 'Location Retrieved';

            setTimeout(function() {
              btnIcon.textContent = '📍';
              btnText.textContent = 'Get Current Location';
              getLocationBtn.disabled = false;
            }, 2000);
          },
          function(error) {
            // Error callback
            let errorMsg = "❌ Unable to retrieve your location.";

            switch (error.code) {
              case error.PERMISSION_DENIED:
                errorMsg = "❌ Location permission denied. Please allow location access in your browser settings.";
                break;
              case error.POSITION_UNAVAILABLE:
                errorMsg = "⚠️ Location information is unavailable. Check your device's location settings.";
                break;
              case error.TIMEOUT:
                errorMsg = "⏳ Location request timed out. Please try again.";
                break;
              default:
                errorMsg = "❌ An unknown error occurred while getting location.";
            }

            showMessage(errorMsg, 'danger');
            console.error('Geolocation error:', error);

            // Reset button
            btnIcon.textContent = '❌';
            btnText.textContent = 'Failed - Try Again';

            setTimeout(function() {
              btnIcon.textContent = '📍';
              btnText.textContent = 'Get Current Location';
              getLocationBtn.disabled = false;
            }, 3000);
          },
          options
        );
      });

      function showMessage(text, type) {
        messageEl.textContent = text;
        messageEl.classList.remove('text-muted', 'text-success', 'text-danger', 'text-warning');

        switch(type) {
          case 'success':
            messageEl.classList.add('text-success');
            break;
          case 'danger':
            messageEl.classList.add('text-danger');
            break;
          case 'warning':
            messageEl.classList.add('text-warning');
            break;
          default:
            messageEl.classList.add('text-muted');
        }
      }
    });
  </script>
@endpush
