@extends('layouts.contentNavbarLayout')
@section('title', 'Employee Summary')

@section('content')
  <section class="p-6 bg-gray-50 min-h-screen space-y-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
      <div>
        <h2 class="text-3xl font-bold text-gray-900">Employee Summary</h2>
        <p class="text-gray-500 mt-1">Summary for <span class="font-medium">{{ $employee->name }}</span></p>
        <p class="text-gray-400 text-sm mt-1">
          From <span class="font-semibold">{{ $from->format('Y-m-d') }}</span>
          to <span class="font-semibold">{{ $to->format('Y-m-d') }}</span>
        </p>
      </div>
    </div>

    {{-- Summary Cards --}}
    <div class="flex-row d-flex mb-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
      @php
        $cards = [
            ['label' => 'Total Hours', 'value' => $summary['total_hours'], 'icon' => '⏱', 'color' => 'bg-indigo-100 text-indigo-800'],
            ['label' => 'Total Earnings', 'value' => '£'.number_format($summary['total_earnings'],2), 'icon' => '💰', 'color' => 'bg-green-100 text-green-800'],
            ['label' => 'Total Pickups', 'value' => $summary['total_pickups'], 'icon' => '📦', 'color' => 'bg-blue-100 text-blue-800'],
            ['label' => 'Total Drops', 'value' => $summary['total_drops'], 'icon' => '📬', 'color' => 'bg-pink-100 text-pink-800'],
            ['label' => 'Mark In Count', 'value' => $summary['mark_in_count'], 'icon' => '🟢', 'color' => 'bg-yellow-100 text-yellow-800'],
            ['label' => 'Mark Out Count', 'value' => $summary['mark_out_count'], 'icon' => '🔴', 'color' => 'bg-red-100 text-red-800'],
        ];
      @endphp

      @foreach($cards as $card)
        <div class="flex flex-col items-center justify-center p-5 bg-white rounded-2xl shadow hover:shadow-lg transition duration-300">
          <div class="text-4xl mb-2">{{ $card['icon'] }}</div>
          <p class="text-gray-500 font-medium">{{ $card['label'] }}</p>
          <p class="text-xl font-bold text-gray-900 mt-1">{{ $card['value'] }}</p>
        </div>
      @endforeach
    </div>

    {{-- Attendance Records --}}
    <div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
      <h3 class="text-2xl font-semibold mb-4 text-gray-800">Attendance Records</h3>
      <table class="min-w-full table-auto divide-y divide-gray-200">
        <thead class="bg-indigo-50 text-indigo-700">
        <tr>
          <th class="p-3 text-left">Date</th>
          <th class="p-3 text-left">Mark In</th>
          <th class="p-3 text-left">Mark Out</th>
          <th class="p-3 text-right">Hours</th>
          <th class="p-3 text-right">Earnings</th>
          <th class="p-3 text-left">Mark In Location</th>
          <th class="p-3 text-left">Mark Out Location</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
        @forelse($filteredAttendances as $att)
          <tr class="hover:bg-gray-50 transition">
            <td class="p-3">{{ $att->mark_in->format('Y-m-d') }}</td>
            <td class="p-3">{{ $att->mark_in->format('H:i') }}</td>
            <td class="p-3">{{ $att->mark_out ? $att->mark_out->format('H:i') : '-' }}</td>
            <td class="p-3 text-right">{{ $att->hour ?? '-' }}</td>
            <td class="p-3 text-right">£{{ number_format($att->earning ?? 0,2) }}</td>
{{--            <td class="p-3">{{ $att->markInLocation->alias ?? '-' }}</td>--}}
{{--            <td class="p-3">{{ $att->markOutLocation->alias ?? '-' }}</td>--}}
            <td class="p-3">{{ isset($att->markInLocations) ? $att->markInLocations->pluck('alias')->join(', ') : '' }}</td>
            <td class="p-3">{{ isset($att->markOutLocations) ? $att->markOutLocations->pluck('alias')->join(', ') : '' }}</td>

          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-gray-400 p-4">No attendance records found.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pickup / Drop Records --}}
    <div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
      <h3 class="text-2xl font-semibold mb-4 text-gray-800">Pickup / Drop Records</h3>
      <table class="min-w-full table-auto divide-y divide-gray-200">
        <thead class="bg-green-50 text-green-700">
        <tr>
          <th class="p-3 text-left">Pickup Vehicle</th>
          <th class="p-3 text-left">Pickup Time</th>
          <th class="p-3 text-left">Drop Vehicle</th>
          <th class="p-3 text-left">Drop Time</th>
          <th class="p-3 text-left">Remarks</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
        @forelse($filteredPickups as $p)
          <tr class="hover:bg-gray-50 transition">
            <td class="p-3">{{ $p->vehicle_number }}</td>
            <td class="p-3">{{ $p->created_at->format('Y-m-d H:i') }}</td>
            <td class="p-3">{{ $p->drop->vehicle_number ?? '-' }}</td>
            <td class="p-3">{{ $p->drop ? $p->drop->created_at->format('Y-m-d H:i') : '-' }}</td>
            <td class="p-3">{{ $p->remarks ?? '-' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-gray-400 p-4">No pickups/drops found.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

  </section>
@endsection
