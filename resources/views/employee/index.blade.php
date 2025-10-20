@extends('layouts/contentNavbarLayout')
@section('title', 'Employee List')

@section('content')

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Employee List</h5>
      <a href="{{ route('employee.create') }}" class="btn btn-primary btn-sm">+ Add Employee</a>
    </div>

    <div class="card-body">
      <table class="table table-hover table-striped table-bordered nowrap" style="width:100%">
        <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Username</th>
          <th>Mark In Location</th>
          <th>Mark Out Location</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($employees as $index => $emp)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>
              <div class="d-flex align-items-center">
                <img src="{{ $emp->image ? Storage::url($emp->image) : 'https://via.placeholder.com/40x40?text=NA' }}"
                     alt="{{ $emp->name }}"
                     class="rounded-circle me-2"
                     width="40"
                     height="40">
                <div>
                  <div class="fw-bold">{{ $emp->name }}</div>
                  <div class="text-muted small">{{ $emp->email }}</div>
                </div>
              </div>
            </td>
            <td>{{ $emp->username }}</td>
            <td>{{ $emp->markInLocation?->alias ?? '-' }}</td>
            <td>{{ $emp->markOutLocation?->alias ?? '-' }}</td>
            <td>
              @if($emp->status == 'active')
                <span class="badge bg-success">Active</span>
              @else
                <span class="badge bg-secondary">Inactive</span>
              @endif
            </td>
            <td>
              <a href="{{ route('employee.view', $emp->id) }}" class="btn btn-sm btn-primary me-1"><i class="bx bx-detail"></i> View</a>

              {{-- Edit Button --}}
              <a href="{{ route('employee.edit', $emp->id) }}" class="btn btn-sm btn-info me-1"><i class="bx bx-edit"></i> Edit</a>

              {{-- Delete Form --}}
              <form action="{{ route('employee.delete', $emp->id) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this employee?')"><i class="bx bx-trash"></i> Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">No employees found.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>

@endsection
