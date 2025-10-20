@extends('layouts.employee')
@section('content')
  <section id="page-earningsReport" class="glass rounded-2xl p-6 shadow-md">
    <div class="flex items-center justify-between mb-3">
      <h2 class="text-lg font-semibold">Earnings Report</h2>
      <div class="flex items-center gap-3">
        <form method="GET" class="flex items-center gap-2">
          <select name="view" class="p-2 border rounded" onchange="this.form.submit()">
            <option value="daily" {{ $view == 'daily' ? 'selected' : '' }}>Daily</option>
            <option value="monthly" {{ $view == 'monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="all" {{ $view == 'all' ? 'selected' : '' }}>All</option>
          </select>
          <noscript><button type="submit" class="px-3 py-2 bg-indigo-500 text-white rounded">Go</button></noscript>
        </form>
      </div>
    </div>

    <div class="overflow-auto max-h-96">
      <table class="w-full text-sm">
        <thead class="bg-slate-100 text-slate-700">
        <tr>
          <th class="text-left p-2">Period</th>
          <th class="text-right p-2">Hours</th>
          <th class="text-right p-2">Earnings</th>
          <th class="text-right p-2">Deductions</th>
          <th class="text-right p-2">Net</th>
        </tr>
        </thead>
        <tbody>
        @forelse($records as $r)
          <tr>
            <td class="p-2">{{ $r->period }}</td>
            <td class="text-right p-2">{{ number_format($r->total_hours ?? 0, 2) }}</td>
            <td class="text-right p-2">{{ number_format($r->total_earnings ?? 0, 2) }}</td>
            <td class="text-right p-2">{{ number_format($r->total_deductions ?? 0, 2) }}</td>
            <td class="text-right p-2">{{ number_format(($r->total_earnings ?? 0) - ($r->total_deductions ?? 0), 2) }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="p-3 text-center text-slate-500">No records found.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3 text-sm">
      Total Net Earnings:
      <span class="font-semibold">
            {{ number_format($records->sum(fn($r) => ($r->total_earnings ?? 0) - ($r->total_deductions ?? 0)), 2) }}
        </span>
    </div>
  </section>
@endsection
