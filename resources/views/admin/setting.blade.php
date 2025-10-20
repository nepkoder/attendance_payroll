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
      <form action="{{ route('timezone.update') }}" method="POST">
        @csrf
        <label for="timezone">Select Timezone</label>
        <select name="timezone" id="timezone" class="form-select" onchange="this.form.submit()">
          @foreach(timezone_identifiers_list() as $tz)
            <option value="{{ $tz }}" {{ config('app.timezone') == $tz ? 'selected' : '' }}>
              {{ $tz }}
            </option>
          @endforeach
        </select>
      </form>

    </div>


  </div>
@endsection
