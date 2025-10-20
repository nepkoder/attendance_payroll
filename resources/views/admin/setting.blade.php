@extends('layouts.contentNavbarLayout')

@section('title', 'Employee Attendance Report')

@section('content')
  <div class="card">

    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Settings</h5>
    </div>

    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
      @endif

      <form action="{{ route('settings.update') }}" method="POST" class="space-y-4">
        @csrf

        {{-- Timezone Selection --}}
        <div class="mb-3">
          <label for="timezone" class="form-label fw-semibold">Select Timezone</label>
          <select name="timezone" id="timezone" class="form-select">
            @foreach(timezone_identifiers_list() as $tz)
              <option value="{{ $tz }}" {{ config('app.timezone') == $tz ? 'selected' : '' }}>
                {{ $tz }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- Radius Coverage --}}
        <div class="mb-3">
          <label for="radius" class="form-label fw-semibold">Coverage Radius (in Meter)</label>
          <input
            type="number"
            name="radius"
            id="radius"
            min="0"
            max="10000"
            class="form-control"
            value="{{ old('radius', env('COVERAGE_RADIUS')) }}"
            placeholder="Enter radius, e.g., 0.5"
          >
          <div class="form-text text-muted small">Define the maximum distance (in Meter) allowed for marking attendance.</div>
        </div>

        <button type="submit" class="btn btn-primary">Save Settings</button>
      </form>

    </div>

  </div>
@endsection
