@extends('layouts.employee')

@section('content')
  <section id="page-attendanceReport" class="glass rounded-2xl p-6 shadow-md">

    <div class="flex items-center justify-between mb-3">
      <h2 class="text-lg font-semibold">Attendance Report</h2>

      {{-- Date Range Filter --}}
      <form method="GET" class="flex gap-2 items-center">
        <label>From:</label>
        <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="form-input rounded px-2 py-1">
        <label>To:</label>
        <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="form-input rounded px-2 py-1">
        <button type="submit" class="px-3 py-1 bg-indigo-500 text-white rounded">Filter</button>
      </form>
    </div>

    {{-- Attendance Table --}}

    {{-- Summary Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
      <div class="bg-white rounded-xl shadow p-4 text-center">
        <div class="text-slate-500 font-semibold">Total Days</div>
        <div class="text-xl font-bold text-slate-800 mt-1">{{ $totalDays }}</div>
      </div>

      <div class="bg-white rounded-xl shadow p-4 text-center">
        <div class="text-slate-500 font-semibold">Total Hours</div>
        <div class="text-xl font-bold text-slate-800 mt-1">{{ $totalHours }}</div>
      </div>

      <div class="bg-white rounded-xl shadow p-4 text-center">
        <div class="text-slate-500 font-semibold">Total Earnings</div>
        <div class="text-xl font-bold text-slate-800 mt-1">£ {{ number_format($totalEarnings,2) }}</div>
      </div>
    </div>

    <div class="overflow-auto max-h-96">
      <table class="w-full text-sm border-collapse border border-slate-200">
        <thead class="bg-slate-100 text-slate-700">
        <tr>
          <th class="p-2 border">Date</th>
          <th class="p-2 border">First In</th>
          <th class="p-2 border">Last Out</th>
          <th class="p-2 border text-right">Hours</th>
          <th class="p-2 border text-right">Earnings</th>
          <th class="p-2 border text-right">Deductions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($report as $row)
          <tr class="hover:bg-slate-50">
            <td class="p-2 border">{{ $row['label'] }}</td>
            <td class="p-2 border">{{ $row['in'] }}</td>
            <td class="p-2 border">{{ $row['out'] }}</td>
            <td class="p-2 border text-right">{{ $row['hours'] }}</td>
            <td class="p-2 border text-right">{{ number_format($row['earnings'],2) }}</td>
            <td class="p-2 border text-right">{{ $row['deductions'] }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="p-3 text-center text-slate-500">No records found.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>



  </section>
@endsection
