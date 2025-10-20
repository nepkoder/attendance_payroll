@extends('layouts.employee')
@section('content')
  <section id="page-earningsReport" class="glass rounded-2xl p-6 shadow-md">

    {{-- Header & Date Filter --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
      <h2 class="text-lg font-semibold">Earnings Report</h2>
      <form method="GET" class="flex gap-3 items-end flex-wrap">
        <div>
          <label class="block text-sm text-gray-600">From:</label>
          <input type="date" name="from" value="{{ $fromDate->format('Y-m-d') }}" class="form-control form-control-sm">
        </div>
        <div>
          <label class="block text-sm text-gray-600">To:</label>
          <input type="date" name="to" value="{{ $toDate->format('Y-m-d') }}" class="form-control form-control-sm">
        </div>
        <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded">Filter</button>
      </form>
    </div>

    {{-- Table --}}
    <div class="overflow-auto max-h-96">
      <table class="w-full text-sm border-collapse border border-slate-200">
        <thead class="bg-slate-100 text-slate-700">
        <tr>
          <th class="text-left p-2 border">Date</th>
          <th class="text-right p-2 border">Hours</th>
          <th class="text-right p-2 border">Earnings</th>
          <th class="text-right p-2 border">Deductions</th>
          <th class="text-right p-2 border">Net</th>
        </tr>
        </thead>
        <tbody>
        @forelse($records as $r)
          <tr>
            <td class="p-2 border">{{ $r['period'] }}</td>
            <td class="text-right p-2 border">{{ number_format($r['total_hours'] ?? 0, 2) }}</td>
            <td class="text-right p-2 border">{{ number_format($r['total_earnings'] ?? 0, 2) }}</td>
            <td class="text-right p-2 border">{{ number_format($r['total_deductions'] ?? 0, 2) }}</td>
            <td class="text-right p-2 border">{{ number_format(($r['total_earnings'] ?? 0) - ($r['total_deductions'] ?? 0), 2) }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="p-3 text-center text-slate-500">No records found.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

    {{-- Summary --}}
    <div class="mt-3 text-sm font-semibold">
      Total Net Earnings:
      <span class="font-semibold">
      {{ number_format($records->sum(fn($r) => ($r['total_earnings'] ?? 0) - ($r['total_deductions'] ?? 0)), 2) }}
    </span>
    </div>

  </section>
@endsection
