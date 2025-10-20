@extends('layouts.contentNavbarLayout')

@section('title', 'Employee Hourly Rates')

@section('content')
  <div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Manage Employee Hourly Rates</h5>
    </div>

    <div class="card-body">
      {{-- Success Message --}}
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="table-responsive text-nowrap">
        <table class="table table-bordered table-hover table-striped align-middle" id="employee-rate-table">
          <thead class="table-dark">
          <tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Email</th>
            <th>Hourly Rate (USD)</th>
            <th>Status</th>
          </tr>
          </thead>
          <tbody>
          @forelse($employees as $employee)
            <tr>
              <td>
                @if($employee->image)
                  <img src="{{ Storage::url($employee->image) }}"
                       alt="{{ $employee->name }}"
                       class="rounded-circle border"
                       width="50" height="50">
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
              <td>{{ $employee->name }}</td>
              <td>{{ $employee->email }}</td>
              <td>
                <form action="{{ route('employee.updateHourlyRate') }}" method="POST" class="d-flex align-items-center">
                  @csrf
                  <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                  <input
                    type="number"
                    step="0.01"
                    name="hourly_rate"
                    value="{{ $employee->hourly_rate }}"
                    class="form-control me-2"
                    style="width:120px;"
                    placeholder="0.00"
                    maxlength="6"
                    oninput="if(this.value.length > 6) this.value = this.value.slice(0,6)"
                    required
                  >
                  <button type="submit" class="btn btn-primary btn-sm"><i class="bx bx-check-circle"></i> Update</button>
                </form>
              </td>
              <td>
                @if($employee->status == 'active')
                  <span class="badge bg-success">Active</span>
                @else
                  <span class="badge bg-secondary">Inactive</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-4">
                <div class="text-muted">
                  No employee records found.
                </div>
              </td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Optional: DataTable Integration --}}
  @push('scripts')
    <script>
      $(document).ready(function() {
        $('#employee-rate-table').DataTable({
          pageLength: 10,
          order: [[1, 'asc']],
          language: {
            emptyTable: "No employee data available"
          }
        });
      });
    </script>
  @endpush
@endsection
