@extends('layouts/contentNavbarLayout')
@section('title', 'Employee Details')

@section('content')

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Employee Details</h5>
      <a href="{{ route('employee.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
    </div>

    <div class="card-body">
      <div class="row">
        <div class="col-md-4 text-center">
          <img src="{{ $employee->image ? Storage::url($employee->image) : 'https://via.placeholder.com/150?text=NA' }}"
               class="rounded-circle mb-3" width="150" height="150">
          <h5>{{ $employee->name }}</h5>
          <span class="badge {{ $employee->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                    {{ ucfirst($employee->status) }}
                </span>
        </div>

        <div class="col-md-8">
          <table class="table table-borderless">
            <tr>
              <th>Username:</th>
              <td>{{ $employee->username }}</td>
            </tr>
            <tr>
              <th>Email:</th>
              <td>{{ $employee->email }}</td>
            </tr>
            <tr>
              <th>Phone:</th>
              <td>{{ $employee->phone ?? '-' }}</td>
            </tr>
            <tr>
              <th>Company / Department:</th>
              <td>{{ $employee->company ?? '-' }} / {{ $employee->department ?? '-' }}</td>
            </tr>
            <tr>
              <th>Address:</th>
              <td>{{ $employee->address ?? '-' }}</td>
            </tr>
            <tr>
              <th>Document No:</th>
              <td>{{ $employee->document_no ?? '-' }}</td>
            </tr>
            <tr>
              <th>Document Image:</th>
              <td>
                @if($employee->document_image)
                  <img src="{{ Storage::url($employee->document_image) }}" width="100">
                @else
                  -
                @endif
              </td>
            </tr>
            <tr>
              <th>Mark In Location:</th>
              <td>{{ isset($employee->markInLocations) ? $employee->markInLocations->pluck('alias')->join(', ') : '' }}</td>
            </tr>
            <tr>
              <th>Mark Out Location:</th>
              <td>{{ isset($employee->markOutLocations) ? $employee->markOutLocations->pluck('alias')->join(', ') : '' }}</td>
            </tr>
            <tr>
              <th>Hourly Rate:</th>
              <td>{{ $employee->hourly_rate ?? '0.00' }}</td>
            </tr>
            <tr>
              <th>Remarks:</th>
              <td>{{ $employee->remarks ?? '-' }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>

@endsection
