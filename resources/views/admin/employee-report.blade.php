@extends('layouts.contentNavbarLayout')
@section('title', 'Employee Report')

@section('content')
  <div class="card">

    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Employee Report</h5>
      <form method="GET" class="d-flex gap-3 align-items-center mb-0">
        <select name="view" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="daily" {{ ($view ?? '') == 'daily' ? 'selected' : '' }}>Daily</option>
          <option value="weekly" {{ ($view ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
          <option value="monthly" {{ ($view ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
        </select>

        <input type="date" name="date" value="{{ $date ?? '' }}" class="form-control form-control-sm"
               onchange="this.form.submit()">
      </form>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
          <tr>
            <th>Employee</th>
            <th>Phone</th>
            <th>Company / Department</th>
            <th>Mark In Location</th>
            <th>Mark Out Location</th>
            <th>Attendance Hours</th>
            <th>Daily Earnings</th>
            <th>Pickup / Drop</th>
            <th>Remarks</th>
          </tr>
          </thead>
          <tbody>
          @foreach($employees as $emp)
            <tr>
              <td>
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <div class="fw-bold">{{ $emp->name }}</div>
                    <div class="text-muted small">{{ $emp->email }}</div>
                  </div>
                  @if($emp->image)
                    <img src="{{ asset('storage/'.$emp->image) }}" class="rounded-circle ms-3" width="50" height="50" alt="{{ $emp->name }}">
                  @endif
                </div>
              </td>
              <td>{{ $emp->phone ?? '-' }}</td>
              <td>{{ $emp->company ?? '-' }} / {{ $emp->department ?? '-' }}</td>
              <td>{{ $emp->markInLocation->alias ?? '-' }}</td>
              <td>{{ $emp->markOutLocation->alias ?? '-' }}</td>

              <td>
                @foreach($emp->filtered_attendances as $att)
                  {{ $att->hour ?? '-' }}<br>
                @endforeach
              </td>

              <td>
                @foreach($emp->filtered_attendances as $att)
                  {{ $att->earning ?? '-' }}<br>
                @endforeach
              </td>

              <td>
                @foreach($emp->filtered_pickups as $p)
                  Pickup: {{ $p->vehicle_number }}<br>
                  @if($p->drop)
                    Drop: {{ $p->drop->vehicle_number ?? '-' }}<br>
                  @endif
                @endforeach
              </td>

              <td>{{ $emp->remarks ?? '-' }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>


  </div>
@endsection
