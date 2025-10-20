@extends('layouts.employee')

@section('content')
  <section id="page-pdReport" class="glass rounded-2xl p-6 shadow-md">

    {{-- Header & Date Filter --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
      <h2 class="text-lg font-semibold">Pickup / Drop Report</h2>
      <form method="GET" action="{{ route('employee.pdreport') }}" class="flex gap-3 items-end flex-wrap">
        <div>
          <label class="block text-sm text-gray-600">From:</label>
          <input type="date" name="from"
                 value="{{ $from ? $from->format('Y-m-d') : '' }}"
                 class="form-control form-control-sm">
        </div>
        <div>
          <label class="block text-sm text-gray-600">To:</label>
          <input type="date" name="to"
                 value="{{ $to ? $to->format('Y-m-d') : '' }}"
                 class="form-control form-control-sm">
        </div>
        <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded">Filter</button>
      </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-4">
      <div class="bg-white rounded-xl shadow p-4 text-center">
        <div class="text-slate-500 font-semibold">Total Pickups</div>
        <div class="text-xl font-bold text-slate-800 mt-1">{{ $summary['total_pickups'] ?? 0 }}</div>
      </div>
      <div class="bg-white rounded-xl shadow p-4 text-center">
        <div class="text-slate-500 font-semibold">Total Drops</div>
        <div class="text-xl font-bold text-slate-800 mt-1">{{ $summary['total_drops'] ?? 0 }}</div>
      </div>
    </div>

    {{-- Table --}}
    <div class="overflow-auto max-h-96">
      <table class="w-full text-sm border-collapse border border-slate-200">
        <thead class="bg-slate-100 text-slate-700">
        <tr>
          <th class="text-left p-2 border">Vehicle</th>
          <th class="text-left p-2 border">Pickup Time</th>
          <th class="text-left p-2 border">Drop Time</th>
          <th class="text-left p-2 border text-center">Pickup Images</th>
          <th class="text-left p-2 border text-center">Drop Images</th>
          <th class="text-right p-2 border">Pickup Remarks</th>
          <th class="text-right p-2 border">Drop Remarks</th>
        </tr>
        </thead>
        <tbody>
        @forelse($pickups as $pickup)
          <tr class="hover:bg-slate-50">
            <td class="p-2 border">{{ $pickup->vehicle_number }}</td>
            <td class="p-2 border">{{ $pickup->created_at->format('Y-m-d H:i') }}</td>
            <td class="p-2 border">{{ $pickup->drop ? $pickup->drop->created_at->format('Y-m-d H:i') : '-' }}</td>

            {{-- Pickup Images --}}
            <td class="p-2 border text-center">
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
            <td class="p-2 border text-center">
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

            <td class="p-2 border">{{ $pickup->remarks ?? '-' }}</td>
            <td class="p-2 border">{{ $pickup->drop->remarks ?? '-' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center p-3 text-slate-500">No entries found.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

  </section>

  {{-- Pickup & Drop Modals --}}
  @include('employee.imageview')
@endsection
