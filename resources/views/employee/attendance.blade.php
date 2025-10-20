@extends('layouts.employee')
@section('content')
  <section id="page-attendanceReport" class="glass rounded-2xl p-6 shadow-md">
    <div class="flex items-center justify-between mb-3">
      <h2 class="text-lg font-semibold">Attendance Report</h2>
      <div class="flex items-center gap-3">
        <a href="{{ route('employee.attendance', ['view' => 'daily']) }}"
           class="px-3 py-2 rounded {{ $view=='daily' ? 'bg-indigo-500 text-white' : 'bg-gray-200 text-gray-700' }}">
          Daily
        </a>
        <a href="{{ route('employee.attendance', ['view' => 'weekly']) }}"
           class="px-3 py-2 rounded {{ $view=='weekly' ? 'bg-indigo-500 text-white' : 'bg-gray-200 text-gray-700' }}">
          Weekly
        </a>
      </div>
    </div>

    <div class="overflow-auto max-h-96">
      <table class="w-full text-sm">
        <thead class="bg-slate-100 text-slate-700">
        <tr>
          <th class="text-left p-2">Date</th>
          <th class="text-left p-2">In</th>
          <th class="text-left p-2">Out</th>
          <th class="text-right p-2">Hours</th>
          <th class="text-right p-2">Earnings</th>
          <th class="text-right p-2">Deductions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($report as $row)
          <tr class="hover:bg-slate-50">
            <td class="border p-2">{{ $row['label'] }}</td>
            <td class="border p-2">{{ $row['in'] }}</td>
            <td class="border p-2">{{ $row['out'] }}</td>
            <td class="border p-2 text-right">{{ $row['hours'] }}</td>
            <td class="border p-2 text-right">{{ number_format($row['earnings'],2) }}</td>
            <td class="border p-2 text-right">{{ $row['deductions'] }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="p-3 text-center text-slate-500">No records found.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3 text-sm">
      Summary: Total Hours: {{ $totalHours }}, Total Earnings: {{ number_format($totalEarnings,2) }}
    </div>
  </section>
@endsection
