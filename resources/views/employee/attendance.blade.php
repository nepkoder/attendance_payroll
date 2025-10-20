@extends('layouts.employee')

@section('content')
  <section id="attendance-report">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
      <h2 class="text-2xl font-bold text-slate-800">Attendance Report</h2>

      {{-- Date Range Filter --}}
      <form method="GET" class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
        <div class="flex items-center gap-1">
          <label class="text-sm font-medium text-slate-600">From:</label>
          <input type="date" name="from" value="{{ $from->format('Y-m-d') }}"
                 class="border border-slate-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="flex items-center gap-1">
          <label class="text-sm font-medium text-slate-600">To:</label>
          <input type="date" name="to" value="{{ $to->format('Y-m-d') }}"
                 class="border border-slate-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <button type="submit"
                class="mt-2 sm:mt-0 px-4 py-1 bg-indigo-600 text-white font-semibold rounded hover:bg-indigo-700 transition">Filter</button>
      </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
      <div class="bg-white rounded-xl shadow p-4 text-center hover:shadow-lg transition">
        <div class="text-sm font-medium text-slate-500">Total Days</div>
        <div class="text-2xl font-bold text-slate-800 mt-1">{{ $totalDays }}</div>
      </div>
      <div class="bg-white rounded-xl shadow p-4 text-center hover:shadow-lg transition">
        <div class="text-sm font-medium text-slate-500">Total Hours</div>
        <div class="text-2xl font-bold text-slate-800 mt-1">{{ $totalHours }}</div>
      </div>
      <div class="bg-white rounded-xl shadow p-4 text-center hover:shadow-lg transition">
        <div class="text-sm font-medium text-slate-500">Total Earnings</div>
        <div class="text-2xl font-bold text-slate-800 mt-1">£{{ number_format($totalEarnings,2) }}</div>
      </div>
    </div>

    {{-- Attendance Table --}}
    <div class="overflow-x-auto rounded-lg shadow-lg">
      <table class="min-w-full text-sm text-left border-collapse border border-slate-200">
        <thead class="bg-indigo-50 text-indigo-700">
        <tr>
          <th class="p-3 border font-medium">Date</th>
          <th class="p-3 border font-medium">First In</th>
          <th class="p-3 border font-medium">Last Out</th>
          <th class="p-3 border font-medium text-right">Hours</th>
          <th class="p-3 border font-medium text-right">Earnings</th>
          <th class="p-3 border font-medium text-right">Deductions</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-slate-200">
        @forelse($report as $row)
          <tr class="hover:bg-indigo-50 transition">
            <td class="p-3 border">{{ $row['label'] }}</td>
            <td class="p-3 border">{{ $row['in'] }}</td>
            <td class="p-3 border">{{ $row['out'] }}</td>
            <td class="p-3 border text-right">{{ $row['hours'] }}</td>
            <td class="p-3 border text-right">£{{ number_format($row['earnings'],2) }}</td>
            <td class="p-3 border text-right">{{ $row['deductions'] }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="p-4 text-center text-slate-500">No records found.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

  </section>
@endsection
