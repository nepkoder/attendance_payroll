@extends('layouts.employee')

@section('content')
  <section id="pd-report" >

    {{-- Header & Date Filter --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
      <h2 class="text-2xl font-bold text-slate-800">Pickup / Drop Report</h2>
      <form method="GET" action="{{ route('employee.pdreport') }}"
            class="flex flex-col sm:flex-row gap-3 items-start sm:items-end flex-wrap">
        <div class="flex flex-col">
          <label class="text-sm font-medium text-slate-600">From:</label>
          <input type="date" name="from"
                 value="{{ $from ? $from->format('Y-m-d') : '' }}"
                 class="border border-slate-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="flex flex-col">
          <label class="text-sm font-medium text-slate-600">To:</label>
          <input type="date" name="to"
                 value="{{ $to ? $to->format('Y-m-d') : '' }}"
                 class="border border-slate-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <button type="submit"
                class="mt-2 sm:mt-0 px-4 py-2 bg-indigo-600 text-white font-semibold rounded hover:bg-indigo-700 transition">
          Filter
        </button>
      </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white rounded-xl shadow p-4 text-center hover:shadow-lg transition">
        <div class="text-sm font-medium text-slate-500">Total Pickups</div>
        <div class="text-2xl font-bold text-slate-800 mt-1">{{ $summary['total_pickups'] ?? 0 }}</div>
      </div>
      <div class="bg-white rounded-xl shadow p-4 text-center hover:shadow-lg transition">
        <div class="text-sm font-medium text-slate-500">Total Drops</div>
        <div class="text-2xl font-bold text-slate-800 mt-1">{{ $summary['total_drops'] ?? 0 }}</div>
      </div>
    </div>

    {{-- Pickup / Drop Table --}}
    <div class="overflow-x-auto rounded-lg shadow-lg">
      <table class="min-w-full text-sm border-collapse border border-slate-200">
        <thead class="bg-indigo-50 text-indigo-700">
        <tr>
          <th class="p-3 border text-left font-medium">Vehicle</th>
          <th class="p-3 border text-left font-medium">Pickup Time</th>
          <th class="p-3 border text-left font-medium">Drop Time</th>
          <th class="p-3 border text-center font-medium">Pickup Images</th>
          <th class="p-3 border text-center font-medium">Drop Images</th>
          <th class="p-3 border text-right font-medium">Pickup Remarks</th>
          <th class="p-3 border text-right font-medium">Drop Remarks</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-slate-200">
        @forelse($pickups as $pickup)
          <tr class="hover:bg-indigo-50 transition">
            <td class="p-3 border">{{ $pickup->vehicle_number }}</td>
            <td class="p-3 border">{{ $pickup->created_at->format('Y-m-d H:i') }}</td>
            <td class="p-3 border">{{ $pickup->drop ? $pickup->drop->created_at->format('Y-m-d H:i') : '-' }}</td>

            {{-- Pickup Images --}}
            <td class="p-3 border text-center">
              @php
                $pickupImages = array_filter(array_merge(
                    $pickup->camera_image ? [$pickup->camera_image] : [],
                    $pickup->images ?? []
                ));
              @endphp
              @if(count($pickupImages) > 0)
                <button class="px-3 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600 text-xs"
                        onclick='openPickupModal(@json($pickupImages))'>
                  View
                </button>
              @else
                <span class="text-slate-400 text-xs">No Images</span>
              @endif
            </td>

            {{-- Drop Images --}}
            <td class="p-3 border text-center">
              @php
                $dropImages = $pickup->drop ? array_filter(array_merge(
                    $pickup->drop->camera_image ? [$pickup->drop->camera_image] : [],
                    $pickup->drop->images ?? []
                )) : [];
              @endphp
              @if(count($dropImages) > 0)
                <button class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-xs"
                        onclick='openDropModal(@json($dropImages))'>
                  View
                </button>
              @else
                <span class="text-slate-400 text-xs">No Images</span>
              @endif
            </td>

            <td class="p-3 border text-right">{{ $pickup->remarks ?? '-' }}</td>
            <td class="p-3 border text-right">{{ $pickup->drop->remarks ?? '-' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center p-4 text-slate-500">No entries found.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

  </section>

  {{-- Pickup & Drop Modals --}}
  @include('employee.imageview')
@endsection
