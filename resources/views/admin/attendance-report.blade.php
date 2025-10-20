@extends('layouts.contentNavbarLayout')

@section('title', 'Employee Attendance Report')

@section('content')
  <div class="card">

    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Attendance Report</h5>
      <form method="GET" class="d-flex gap-3 align-items-center mb-0">
        <select name="view" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="daily" {{ ($view ?? '') == 'daily' ? 'selected' : '' }}>Daily</option>
          <option value="weekly" {{ ($view ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
          <option value="monthly" {{ ($view ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
        </select>
        {{--        <input type="date" name="date" value="{{ $date ?? '' }}" class="form-control form-control-sm"--}}
        {{--               onchange="this.form.submit()">--}}
      </form>
    </div>

    <div class="card-body">

      <!-- Summary Cards Grid -->
      <!-- Summary Cards Grid with Row & Column separation -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow p-6 text-center hover:scale-105 transition transform duration-200">
          <div class="text-slate-500 font-semibold">Total Employees</div>
          <div class="text-3xl font-bold text-slate-800 mt-2">{{ $summary['total_employees'] ?? 0 }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 text-center hover:scale-105 transition transform duration-200">
          <div class="text-slate-500 font-semibold">Total Mark-ins</div>
          <div class="text-3xl font-bold text-slate-800 mt-2">{{ $summary['total_mark_ins'] ?? 0 }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 text-center hover:scale-105 transition transform duration-200">
          <div class="text-slate-500 font-semibold">Total Hours Worked</div>
          <div class="text-3xl font-bold text-slate-800 mt-2">{{ $summary['total_hours'] ?? '0:00' }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 text-center hover:scale-105 transition transform duration-200">
          <div class="text-slate-500 font-semibold">Total Earnings</div>
          <div class="text-3xl font-bold text-slate-800 mt-2">£ {{ number_format($summary['total_earning'] ?? 0,2) }}</div>
        </div>
      </div>



      <!-- Attendance Table -->
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
          <tr>
            <th>Employee</th>
            <th>Date</th>
            <th>Mark In</th>
            <th>Mark Out</th>
            <th>Hours Worked</th>
            <th>Hourly Rate</th>
            <th>Earning</th>
            <th>Mark In Location</th>
            <th>Mark Out Location</th>
          </tr>
          </thead>
          <tbody>
          @forelse($attendances as $att)
            <tr>
              <td>{{ $att->employee->name }}</td>
              <td>{{ $att->mark_in ? $att->mark_in->format('Y-m-d') : '-' }}</td>
              <td>{{ $att->mark_in ? $att->mark_in->format('h:i A') : '-' }}</td>
              <td>{{ $att->mark_out ? $att->mark_out->format('h:i A') : '-' }}</td>
              <td>{{ $att->hour ?? '0' }}</td>
              <td>£ {{ $att->hourly_rate ?? '0' }}</td>
              <td>£ {{ number_format($att->earning ?? '0',2) }}</td>
              <td>{{ $att->employee->markInLocation?->alias ?? '-' }}</td>
              <td>{{ $att->employee->markOutLocation?->alias ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center p-3 text-slate-500">No attendance records found.</td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>


  </div>
@endsection
