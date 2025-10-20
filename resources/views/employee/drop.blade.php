@extends('layouts.employee')

@section('content')
  <!-- ✅ PICKUP ENTRY FORM -->
  <section id="page-pickup" class="glass rounded-2xl p-6 shadow-md mb-8">
    <h2 class="text-lg font-semibold mb-3">Pickup Entry</h2>

    <form action="{{ route('vehicle.drop.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
      @csrf
      <div>
        <label class="block text-sm font-medium">Select Vehicle (from Pickups)</label>
        <select name="pickup_id" class="w-full mt-1 p-3 border rounded-lg" required>
          <option value="">-- Select Pickup --</option>
          @foreach($pendingPickups as $pickup)
            <option value="{{ $pickup->id }}">{{ $pickup->vehicle_number }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium">Capture Vehicle (Camera)</label>
        <button type="button" id="openCameraBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
          Open Camera
        </button>

        <!-- Preview below button -->
        <div id="capturedPreviewContainer" class="mt-3 hidden relative w-64">
          <img id="capturedPreview" class="rounded-lg border w-full" alt="Captured Image">
          <button type="button" id="removeCapturedImage"
                  class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">✕</button>
        </div>

        <!-- Hidden input to store captured image -->
        <input type="hidden" name="camera_image" id="camera_image_input">
      </div>

      <div>
        <label class="block text-sm font-medium">Upload Vehicle Images</label>
        <input name="images[]" type="file" multiple accept="image/*" class="w-full mt-1">
      </div>

      <div>
        <label class="block text-sm font-medium">Remarks</label>
        <textarea name="remarks" class="w-full p-3 border rounded-lg"></textarea>
      </div>

      <button type="submit" class="px-5 py-3 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-semibold">
        Save Drop
      </button>
    </form>
  </section>

  <!-- ✅ CAMERA MODAL -->
  <div id="cameraModal" class="hidden fixed inset-0 bg-black/70 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 relative w-full max-w-4xl">
      <button onclick="closeCamera()" class="absolute top-3 right-3 text-white bg-red-600 rounded-full w-8 h-8 flex items-center justify-center font-bold">✕</button>
      <h3 class="text-lg font-semibold mb-3">Capture Vehicle</h3>

      <video id="cameraVideo" autoplay playsinline class="w-full rounded-lg border"></video>

      <div class="flex justify-end gap-4 mt-4">
        <button id="captureBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Capture</button>
        <button id="closeModalBtn" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600" onclick="closeCamera()">Close</button>
      </div>
    </div>
  </div>

  <!-- ✅ HISTORY TABLE -->
  <section class="mt-8">
    <h2 class="text-lg font-semibold mb-3">Pickup & Drop Records</h2>

    <div class="overflow-auto max-h-[60vh] rounded-xl border border-slate-200 shadow">
      <table class="w-full border text-sm">
        <thead class="bg-slate-200">
        <tr>
          <th class="p-2 border text-start">Vehicle No</th>
          <th class="p-2 border text-start">Pickup Time</th>
          <th class="p-2 border text-start">Drop Time</th>
          <th class="p-2 border text-start">Remarks</th>
          <th class="p-2 border text-center">Status</th>
          <th class="p-2 border text-center">Images</th>
        </tr>
        </thead>
        <tbody>
        @forelse($pickups as $pickup)
          <tr class="align-top hover:bg-slate-50">
            <td class="border p-2 font-semibold">{{ $pickup->vehicle_number }}</td>
            <td class="border p-2">{{ $pickup->created_at->format('Y-m-d H:i') }}</td>
            <td class="border p-2">
              {{ $pickup->drop ? $pickup->drop->created_at->format('Y-m-d H:i') : '-' }}
            </td>
            <td class="border p-2 text-slate-700">{{ $pickup->remarks ?? '-' }}</td>

            <td class="border p-2 text-center">
              @if($pickup->drop)
                <span class="text-green-600 font-semibold">Dropped</span>
              @else
                <span class="text-amber-600 font-semibold">Pending</span>
              @endif
            </td>

            <td class="border p-2 text-center">
              @php
                $pickupImages = array_filter(array_merge(
                  $pickup->camera_image ? [$pickup->camera_image] : [],
                  $pickup->images ?? []
                ));
                $dropImages = array_filter(array_merge(
                  $pickup->drop?->camera_image ? [$pickup->drop->camera_image] : [],
                  $pickup->drop?->images ?? []
                ));
                $allImages = array_merge($pickupImages, $dropImages);
              @endphp

              @if(count($allImages) > 0)
                <button
                  class="px-3 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600 text-xs"
                  onclick="openImageModal({{ json_encode($allImages) }})">
                  View Images
                </button>
              @else
                <span class="text-slate-400 text-xs">No Images</span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="p-3 text-center text-slate-500">No entries yet.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </section>

  <!-- ✅ IMAGE VIEW MODAL (Slider View) -->
  <div id="imageModal" style="margin-top: 0 !important;" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-50">
    <button onclick="closeImageModal()"
            class="absolute top-4 right-4 text-white bg-red-600 hover:bg-red-700 rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg z-50">
      ✕
    </button>

    <div class="relative max-w-4xl w-full bg-white rounded-lg overflow-hidden p-4 flex flex-col items-center">
      <img id="modalMainImage" src="" alt="Image" class="max-h-[75vh] w-auto rounded-lg shadow-lg mb-4">
      <div class="flex justify-between items-center w-full mt-2">
        <button id="prevBtn" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg font-semibold text-slate-700">◀ Prev</button>
        <div id="imageCounter" class="text-slate-600 text-sm"></div>
        <button id="nextBtn" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg font-semibold text-slate-700">Next ▶</button>
      </div>
      <div id="thumbnailContainer" class="flex gap-2 mt-4 overflow-x-auto max-w-full p-2"></div>
    </div>
  </div>

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
        thumb.className = 'h-16 w-auto rounded-md border cursor-pointer hover:opacity-80 transition';
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

      imgEl.src = modalImages[currentIndex];
      counter.textContent = `Image ${currentIndex + 1} of ${modalImages.length}`;
    }

    function closeImageModal() {
      document.getElementById('imageModal').classList.add('hidden');
    }

    document.getElementById('prevBtn').addEventListener('click', () => showImage(currentIndex - 1));
    document.getElementById('nextBtn').addEventListener('click', () => showImage(currentIndex + 1));
    document.addEventListener('keydown', (e) => {
      const modal = document.getElementById('imageModal');
      if (modal.classList.contains('hidden')) return;
      if (e.key === 'ArrowRight') showImage(currentIndex + 1);
      if (e.key === 'ArrowLeft') showImage(currentIndex - 1);
      if (e.key === 'Escape') closeImageModal();
    });

    // CAMERA LOGIC
    let stream;
    const openCameraBtn = document.getElementById('openCameraBtn');
    const cameraModal = document.getElementById('cameraModal');
    const video = document.getElementById('cameraVideo');
    const canvas = document.createElement('canvas');

    const capturedPreviewContainer = document.getElementById('capturedPreviewContainer');
    const capturedPreview = document.getElementById('capturedPreview');
    const removeCapturedImage = document.getElementById('removeCapturedImage');
    const cameraImageInput = document.getElementById('camera_image_input');

    openCameraBtn.addEventListener('click', async () => {
      cameraModal.classList.remove('hidden');
      try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
        video.srcObject = stream;
      } catch (err) {
        alert('Could not access camera: ' + err);
      }
    });

    document.getElementById('captureBtn').addEventListener('click', () => {
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      canvas.getContext('2d').drawImage(video, 0, 0);
      const imageDataUrl = canvas.toDataURL('image/jpeg');

      capturedPreview.src = imageDataUrl;
      capturedPreviewContainer.classList.remove('hidden');
      cameraImageInput.value = imageDataUrl;

      closeCamera();
    });

    removeCapturedImage.addEventListener('click', () => {
      capturedPreviewContainer.classList.add('hidden');
      capturedPreview.src = '';
      cameraImageInput.value = '';
    });

    function closeCamera() {
      cameraModal.classList.add('hidden');
      if (stream) stream.getTracks().forEach(track => track.stop());
    }
  </script>
@endsection
