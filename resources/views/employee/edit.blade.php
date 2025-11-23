@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Employee')

@section('content')

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>


  <div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Edit Employee - {{ $employee->name }}</h5>
      <small class="text-body-secondary float-end">* Fields are required</small>
      <a href="{{ route('employee.index') }}" class="btn btn-secondary btn-sm">Back to List</a>

    </div>

    <div class="card-body">
      <form action="{{ route('employee.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <!-- Left Column -->
          <div class="col-md-6">
            <div class="mb-3">
              <label for="name" class="form-label">Name *</label>
              <input
                type="text"
                name="name"
                class="form-control"
                id="name"
                value="{{ old('name', $employee->name) }}"
                required
              >
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email *</label>
              <input
                type="email"
                name="email"
                class="form-control"
                id="email"
                value="{{ old('email', $employee->email) }}"
                required
              >
            </div>

            <div class="mb-3">
              <label for="username" class="form-label">Username *</label>
              <input
                type="text"
                name="username"
                class="form-control"
                id="username"
                value="{{ old('username', $employee->username) }}"
                required
              >
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password (leave blank to keep unchanged)</label>
              <input
                type="password"
                name="password"
                class="form-control"
                id="password"
                placeholder="********"
              >
            </div>

            <div class="mb-3">
              <label for="phone" class="form-label">Phone No.</label>
              <input
                type="text"
                name="phone"
                class="form-control"
                id="phone"
                value="{{ old('phone', $employee->phone) }}"
                placeholder="+977 980000001"
              >
            </div>

            <div class="mb-3">
              <label class="form-label">Mark In Locations</label>
              <select name="mark_in_location_id[]" id="markIn" multiple>
                @foreach($locations as $loc)
                  <option value="{{ $loc->id }}"
                    {{ $employee->markInLocations->contains($loc->id) ? 'selected' : '' }}>
                    {{ $loc->alias }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Mark Out Locations</label>
              <select name="mark_out_location_id[]" id="markOut" multiple>
                @foreach($locations as $loc)
                  <option value="{{ $loc->id }}"
                    {{ $employee->markOutLocations->contains($loc->id) ? 'selected' : '' }}>
                    {{ $loc->alias }}
                  </option>
                @endforeach
              </select>
            </div>


          </div>

          <!-- Right Column -->
          <div class="col-md-6">

            <div class="mb-3">
              <label for="hourly_rate" class="form-label">Hourly Rate</label>
              <input
                type="number"
                name="hourly_rate"
                class="form-control"
                id="hourly_rate"
                value="{{ old('hourly_rate', $employee->hourly_rate) }}"
                placeholder="Rate in £"
              >
            </div>

            <div class="mb-3">
              <label for="department" class="form-label">Department</label>
              <input
                type="text"
                name="department"
                class="form-control"
                id="department"
                value="{{ old('department', $employee->department) }}"
                placeholder="HR"
              >
            </div>

            <div class="mb-3">
              <label for="company" class="form-label">Company</label>
              <input
                type="text"
                name="company"
                class="form-control"
                id="company"
                value="{{ old('company', $employee->company) }}"
                placeholder="ACME Inc."
              >
            </div>

            <div class="mb-3">
              <label for="document_no" class="form-label">Document No.</label>
              <input
                type="text"
                name="document_no"
                class="form-control"
                id="document_no"
                value="{{ old('document_no', $employee->document_no) }}"
                placeholder="123456789"
              >
            </div>

            <div class="mb-3">
              <label for="document_image" class="form-label">Document Image</label>
              <input type="file" name="document_image" class="form-control" id="document_image">
              @if($employee->document_image)
                <div class="mt-2">
                  <a href="{{ asset('storage/' . $employee->document_image) }}" target="_blank">
                    <img src="{{ asset('storage/' . $employee->document_image) }}" alt="Document" height="60">
                  </a>
                </div>
              @endif
            </div>

            <div class="mb-3">
              <label for="image" class="form-label">Employee Image</label>
              <input type="file" name="image" class="form-control" id="image">
              @if($employee->image)
                <div class="mt-2">
                  <img src="{{ asset('storage/' . $employee->image) }}" alt="Employee" height="60" class="rounded">
                </div>
              @endif
            </div>
            <div class="mb-3">
              <label for="status" class="form-label">Status *</label>
              <select name="status" id="status" class="form-select" required>
                <option value="active" {{ isset($employee) && $employee->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ isset($employee) && $employee->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
              </select>
            </div>

{{--            <div class="mb-3">--}}
{{--              <label for="remarks" class="form-label">Remarks</label>--}}
{{--              <textarea--}}
{{--                name="remarks"--}}
{{--                id="remarks"--}}
{{--                class="form-control"--}}
{{--                rows="3"--}}
{{--                placeholder="Enter notes or details..."--}}
{{--              >{{ old('remarks', $employee->remarks) }}</textarea>--}}
{{--            </div>--}}
          </div>
        </div>

        <div class="mt-4 float-end">
          <button type="submit" class="btn btn-primary">Update Employee</button>
          <a href="{{ route('employee.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    new Choices('#markIn', {
      removeItemButton: true,
      searchPlaceholderValue: 'Search mark-in locations...',
    });

    new Choices('#markOut', {
      removeItemButton: true,
    });
  </script>


@endsection
