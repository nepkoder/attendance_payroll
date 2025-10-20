@extends('layouts.employee')

@section('content')
  <section id="earnings-report">

    {{-- Header & Date Filter --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
      <h2 class="text-2xl font-bold text-slate-800">Earnings Report</h2>
      <form method="GET" class="flex flex-col sm:flex-row gap-3 items-start sm:items-end flex-wrap">
        <div class="flex flex-col">
          <label class="text-sm font-medium text-slate-600">From:</label>
          <input type="date" name="from" value="{{ $fromDate->format('Y-m-d') }}"
                 class="border border-slate-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="flex flex-col">
          <label class="text-sm font-medium text-slate-600">To:</label>
          <input type="date" name="to" value="{{ $toDate->format('Y-m-d') }}"
                 class="border border-slate-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <button type="submit"
                class="mt-2 sm:mt-0 px-4 py-2 bg-indigo-600 text-white font-semibold rounded hover:bg-indigo-700 transition">
          Filter
        </button>
      </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-lg shadow-lg">
      <table class="min-w-full text-sm border-collapse border border-slate-200">
        <thead class="bg-indigo-50 text-indigo-700">
        <tr>
          <th class="p-3 border text-left font-medium">Date</th>
          <th class="p-3 border text-right font-medium">Hours</th>
          <th class="p-3 border text-right font-medium">Earnings</th>
          <th class="p-3 border text-right font-medium">Deductions</th>
          <th class="p-3 border text-right font-medium">Net</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-slate-200">
        @forelse($records as $r)
          <tr class="hover:bg-indigo-50 transition">
            <td class="p-3 border">{{ $r['period'] }}</td>
            <td class="text-right p-3 border">{{ number_format($r['total_hours'] ?? 0, 2) }}</td>
            <td class="text-right p-3 border">£{{ number_format($r['total_earnings'] ?? 0, 2) }}</td>
            <td class="text-right p-3 border">£{{ number_format($r['total_deductions'] ?? 0, 2) }}</td>
            <td class="text-right p-3 border font-semibold">£{{ number_format(
                            ($r['total_earnings'] ?? 0) - ($r['total_deductions'] ?? 0), 2) }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center p-4 text-slate-500">No records found.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

    {{-- Summary Card --}}
    <div class="mt-6">
      <div class="bg-white rounded-xl shadow p-4 text-center w-full sm:w-1/3">
        <div class="text-sm font-medium text-slate-500">Total Net Earnings</div>
        <div class="text-2xl font-bold text-slate-800 mt-1">
          £{{ number_format($records->sum(fn($r) => ($r['total_earnings'] ?? 0) - ($r['total_deductions'] ?? 0)), 2) }}
        </div>
      </div>
    </div>

  </section>
@endsection
