@extends('layouts.contentNavbarLayout')
@section('title', 'Employee Report')

@section('content')
  <div class="card">

    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Pickup / Drop off Report</h5>
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

      <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
          <thead class="table-dark">
          <tr>
            <th>Pickup Date</th>
            <th>Vehicle No</th>
            <th>Pickup Images</th>
            <th>Pickup Employee</th>
            <th>Pickup Remarks</th>
            <th>Dropoff Date</th>
            <th>Dropoff Images</th>
            <th>Dropoff Employee</th>
            <th>Dropoff Remarks</th>
          </tr>
          </thead>

          <tbody>
          @forelse($pickups as $pickup)
            <tr>

              {{-- Pickup Date --}}
              <td class="p-3">
                {{ $pickup->created_at?->format('Y-m-d H:i') ?? '-' }}
              </td>

              {{-- Vehicle Number --}}
              <td class="p-3">{{ $pickup->vehicle_number }}</td>

              {{-- Pickup Images --}}
              <td class="p-3">
                @if(!empty($pickup->image_urls))
                  <div class="d-flex gap-2 flex-wrap">
                    @foreach($pickup->image_urls as $img)
                      <img src="{{ $img }}"
                           onclick="showImage('{{ $img }}')"
                           class="rounded cursor-pointer"
                           width="60" height="60"
                           style="object-fit: cover;">
                    @endforeach

                  </div>
                @else
                  -
                @endif
              </td>

              <td>
                {{$pickup->pickupEmployee ?? '-'}}
              </td>

              {{-- Pickup Remarks --}}
              <td class="p-3">
                {{ $pickup->remarks ?? '-' }}
              </td>

              {{-- Dropoff Date --}}
              <td class="p-3">
                {{ $pickup->drop?->created_at?->format('Y-m-d H:i') ?? '-' }}
              </td>

              {{-- Dropoff Images --}}
              <td class="p-3">
                @if(!empty($pickup->drop?->image_urls))
                  <div class="d-flex gap-2 flex-wrap">
                    @foreach($pickup->drop->image_urls as $dimg)
                      <img src="{{ $dimg }}"
                           onclick="showImage('{{ $dimg }}')"
                           class="rounded cursor-pointer"
                           width="60" height="60"
                           style="object-fit: cover;">
                    @endforeach

                  </div>
                @else
                  -
                @endif
              </td>

              <td>
                {{$pickup->dropoffEmployee ?? '-'}}
              </td>

              {{-- Dropoff Remarks --}}
              <td class="p-3">
                {{ $pickup->drop?->remarks ?? '-' }}
              </td>

            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center p-4 text-muted">
                No records found.
              </td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>


    </div>

  </div>

  <!-- Image Preview Modal -->
  <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-body p-0">
          <img id="modalImage" src="" class="w-100" style="object-fit: contain;">
        </div>
      </div>
    </div>
  </div>

  <script>
    function showImage(src) {
      document.getElementById('modalImage').src = src;
      var myModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
      myModal.show();
    }
  </script>


@endsection
