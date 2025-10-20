@extends('layouts.employee')

@section('content')
  <section id="page-pdReport" class="glass rounded-2xl p-6 shadow-md">

    <div class="flex items-center justify-between mb-3">
      <h2 class="text-lg font-semibold">Pickup / Drop Report</h2>
      <div class="flex items-center gap-3">
        <form method="GET" action="{{ route('employee.pdreport') }}">
          <select name="view" class="p-2 border rounded" onchange="this.form.submit()">
            <option value="daily" {{ $view == 'daily' ? 'selected' : '' }}>Daily</option>
            <option value="monthly" {{ $view == 'monthly' ? 'selected' : '' }}>Monthly</option>
          </select>
        </form>
      </div>
    </div>

    <div class="overflow-auto max-h-96">
      <table class="w-full text-sm border">
        <thead class="bg-slate-100 text-slate-700">
        <tr>
          <th class="text-left p-2 border">Type</th>
          <th class="text-left p-2 border">Vehicle</th>
          <th class="text-left p-2 border">Time</th>
          <th class="text-left p-2 border text-center">Images</th>
          <th class="text-right p-2 border">Remarks</th>
        </tr>
        </thead>
        <tbody>
        @forelse($pickups as $pickup)
          {{-- Pickup row --}}
          <tr>
            <td class="p-2 border">Pickup</td>
            <td class="p-2 border">{{ $pickup->vehicle_number }}</td>
            <td class="p-2 border">{{ $pickup->created_at->format('Y-m-d H:i') }}</td>
            <td class="p-2 border text-center">
              @php
                $pickupImages = array_filter(array_merge(
                  $pickup->camera_image ? [$pickup->camera_image] : [],
                  $pickup->images ?? []
                ));
              @endphp
              @if(count($pickupImages) > 0)
                <button
                  class="px-3 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600 text-xs"
                  onclick='openImageModal(@json($pickupImages))'>
                  View Images
                </button>
              @else
                <span class="text-slate-400 text-xs">No Images</span>
              @endif
            </td>
            <td class="p-2 border">{{ $pickup->remarks ?? '-' }}</td>
          </tr>

          {{-- Drop row --}}
          @if($pickup->drop)
            @php
              $dropImages = array_filter(array_merge(
                $pickup->drop->camera_image ? [$pickup->drop->camera_image] : [],
                $pickup->drop->images ?? []
              ));
            @endphp
            <tr class="bg-slate-50">
              <td class="p-2 border">Drop</td>
              <td class="p-2 border">{{ $pickup->vehicle_number }}</td>
              <td class="p-2 border">{{ $pickup->drop->created_at->format('Y-m-d H:i') }}</td>
              <td class="p-2 border text-center">
                @if(count($dropImages) > 0)
                  <button
                    class="px-3 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600 text-xs"
                    onclick='openImageModal(@json($dropImages))'>
                    View Images
                  </button>
                @else
                  <span class="text-slate-400 text-xs">No Images</span>
                @endif
              </td>
              <td class="p-2 border">{{ $pickup->drop->remarks ?? '-' }}</td>
            </tr>
          @endif

        @empty
          <tr>
            <td colspan="5" class="text-center p-3 text-slate-500">No entries found.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3 text-sm">
      Summary: Total Pickups {{ $summary['total_pickups'] }}, Total Drops {{ $summary['total_drops'] }}
    </div>

  </section>

  <!-- ✅ IMAGE VIEW MODAL (Slider) -->
  <div id="imageModal" style="margin-top: 0 !important;"
       class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-50">
    <button onclick="closeImageModal()"
            class="absolute top-4 right-4 text-white bg-red-600 hover:bg-red-700 rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg z-50">
      ✕
    </button>

    <div class="relative max-w-4xl w-full bg-white rounded-lg overflow-hidden p-4 flex flex-col items-center">
      <img id="modalMainImage" src="" alt="Image" class="max-h-[75vh] w-auto rounded-lg shadow-lg mb-4">

      <div class="flex justify-between items-center w-full mt-2">
        <button id="prevBtn"
                class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg font-semibold text-slate-700">
          ◀ Prev
        </button>
        <div id="imageCounter" class="text-slate-600 text-sm"></div>
        <button id="nextBtn"
                class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg font-semibold text-slate-700">
          Next ▶
        </button>
      </div>

      <div id="thumbnailContainer" class="flex gap-2 mt-4 overflow-x-auto max-w-full p-2"></div>
    </div>
  </div>

  <style>
    #modalMainImage {
      transition: opacity 0.3s ease-in-out;
    }
  </style>

  <script>
    let currentIndex = 0;
    let modalImages = [];

    function openImageModal(images) {
      modalImages = images.map(img => '/storage/' + img);
      currentIndex = 0;
      showImage(currentIndex);

      const thumbnailContainer = document.getElementById('thumbnailContainer');
      thumbnailContainer.innerHTML = '';

      modalImages.forEach((src, index) => {
        const thumb = document.createElement('img');
        thumb.src = src;
        thumb.className =
          'h-16 w-auto rounded-md border cursor-pointer hover:opacity-80 transition';
        thumb.onclick = () => showImage(index);
        thumbnailContainer.appendChild(thumb);
      });

      document.getElementById('imageModal').classList.remove('hidden');
    }

    function showImage(index) {
      const imgEl = document.getElementById('modalMainImage');
      const counter = document.getElementById('imageCounter');

      if (index < 0) index = modalImages.length - 1;
      if (index >= modalImages.length) index = 0;
      currentIndex = index;

      imgEl.style.opacity = 0;
      setTimeout(() => {
        imgEl.src = modalImages[currentIndex];
        imgEl.onload = () => imgEl.style.opacity = 1;
      }, 150);

      counter.textContent = `Image ${currentIndex + 1} of ${modalImages.length}`;
    }

    function closeImageModal() {
      document.getElementById('imageModal').classList.add('hidden');
    }

    document.getElementById('prevBtn').addEventListener('click', () => {
      showImage(currentIndex - 1);
    });
    document.getElementById('nextBtn').addEventListener('click', () => {
      showImage(currentIndex + 1);
    });

    document.addEventListener('keydown', (e) => {
      const modal = document.getElementById('imageModal');
      if (modal.classList.contains('hidden')) return;

      if (e.key === 'ArrowRight') showImage(currentIndex + 1);
      if (e.key === 'ArrowLeft') showImage(currentIndex - 1);
      if (e.key === 'Escape') closeImageModal();
    });
  </script>
@endsection
