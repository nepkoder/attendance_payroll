@extends('layouts/contentNavbarLayout')
@section('title', 'Create Employee')
@section('content')

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>


  <div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Create New Employee</h5>
      <small class="text-body-secondary float-end">* Fields are required</small>
      <a href="{{ route('employee.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
    </div>

    <div class="card-body">
      <form action="{{ route('employee.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <!-- Left -->
          <div class="col-md-6">
            <div class="mb-3">
              <label>Name *</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Email *</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Username *</label>
              <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Password *</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Phone</label>
              <input type="number" name="phone" class="form-control">
            </div>

            <div class="mb-3">
              <label>Mark In Locations</label>
              <select name="mark_in_location_id[]" id="markIn" multiple>
                @foreach($locations as $loc)
                  <option value="{{ $loc->id }}">{{ $loc->alias }}</option>
                @endforeach
              </select>
            </div>


            <div class="mb-3">
              <label>Mark Out Locations</label>
              <select name="mark_out_location_id[]" id="markOut" multiple>
                @foreach($locations as $loc)
                  <option value="{{ $loc->id }}">{{ $loc->alias }}</option>
                @endforeach
              </select>


            </div>


          </div>

          <!-- Right -->
          <div class="col-md-6">
            <div class="mb-3">
              <label>Hourly Rate</label>
              <input type="number" name="hourly_rate" class="form-control">
            </div>
            <div class="mb-3">
              <label>Department</label>
              <input type="text" name="department" class="form-control">
            </div>
            <div class="mb-3">
              <label>Company</label>
              <input type="text" name="company" class="form-control">
            </div>
            <div class="mb-3">
              <label for="status" class="form-label">Status *</label>
              <select name="status" id="status" class="form-select" required>
                <option value="active" {{ isset($employee) && $employee->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ isset($employee) && $employee->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
              </select>
            </div>
            <div class="mb-3">
              <label>Document No.</label>
              <input type="text" name="document_no" class="form-control">
            </div>
            <div class="mb-3">
              <label>Document Image</label>
              <input type="file" name="document_image" class="form-control">
            </div>
            <div class="mb-3">
              <label>Employee Image</label>
              <input type="file" name="image" class="form-control">
            </div>
          </div>
        </div>
        <div class="mb-3 mt-4 float-end">
          <button type="submit" class="btn btn-primary">Create Employee</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    new Choices('#markIn', {
      removeItemButton: true,
      searchPlaceholderValue: 'Search locations...',
    });
    new Choices('#markOut', {
      removeItemButton: true,
    });
  </script>



@endsection
