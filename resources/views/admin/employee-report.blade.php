@extends('layouts.contentNavbarLayout')
@section('title', 'Employee Report')

@section('content')
  <div class="card">

    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Employee Report</h5>
      <form method="GET" class="d-flex flex-col sm:flex-row gap-3 items-start sm:items-end flex-wrap">
        <div class="flex flex-col">
          <label class="text-sm text-slate-600">From:</label>
          <input type="date" name="from" value="{{ $from->format('Y-m-d') }}"
                 class="border rounded px-3 py-1 focus:ring-1 focus:ring-indigo-500">
        </div>
        <div class="flex flex-col">
          <label class="text-sm text-slate-600">To:</label>
          <input type="date" name="to" value="{{ $to->format('Y-m-d') }}"
                 class="border rounded px-3 py-1 focus:ring-1 focus:ring-indigo-500">
        </div>
        <button type="submit"
                class="btn btn-sm btn-primary">
          Filter
        </button>
      </form>
    </div>

    <div class="card-body">

      {{-- Table --}}
      <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
          <thead class="table-dark">
          <tr>
            <th>Employee</th>
            <th>Phone</th>
            <th>Company / Dept</th>
            <th>Total Hours</th>
            <th>Total Earnings</th>
            <th>Pickups</th>
            <th>Drops</th>
            <th>Mark In Count</th>
            <th>Mark Out Count</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          @forelse($employees as $emp)
            <tr>
              <td class="p-3 flex items-center gap-2">
                @if($emp->image)
                  <img src="{{ asset('storage/'.$emp->image) }}" class="w-10 h-10 rounded-full object-cover">
                @endif
                <div class="truncate">
                  <div class="font-medium">{{ $emp->name }}</div>
                  <div class="text-xs text-slate-500 truncate">{{ $emp->email }}</div>
                </div>
              </td>
              <td class="p-3">{{ $emp->phone ?? '-' }}</td>
              <td class="p-3">{{ $emp->company ?? '-' }} / {{ $emp->department ?? '-' }}</td>
              <td class="p-3 text-right">{{ $emp->total_hours }}</td>
              <td class="p-3 text-right">£{{ number_format($emp->total_earnings,2) }}</td>
              <td class="p-3 text-right">{{ $emp->total_pickups }}</td>
              <td class="p-3 text-right">{{ $emp->total_drops }}</td>
              <td class="p-3 text-right">{{ $emp->mark_in_count }}</td>
              <td class="p-3 text-right">{{ $emp->mark_out_count }}</td>
              <td class="p-3 text-center">
                <a
                  href="{{ route('employee.summary', ['employee' => $emp->id, 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}"
                  class="btn btn-sm btn-info">
                  View Summary
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center p-4 text-slate-500">No records found.</td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
@endsection
