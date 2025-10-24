@extends('layouts.employee')

@section('content')
  <section id="page-pickup" class="glass rounded-2xl p-6 shadow-md mb-8">
    <h2 class="text-lg font-semibold mb-3">Pickup Entry</h2>

    <form action="{{ route('vehicle.pickup.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="pickupForm">
      @csrf

      <div>
        <label class="block text-sm font-medium">Vehicle Number</label>
        <input name="vehicle_number" type="text" class="w-full p-3 border rounded-lg" required>
      </div>

      <!-- CAMERA & DROPZONE ROW -->
      <div class="flex flex-wrap gap-4 mt-2">
        <!-- DROPZONE -->
        <div id="dropzone"
             class="flex-1 min-w-[220px] p-6 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
          </svg>
          <p class="text-gray-500 font-semibold">Drag & Drop images here or click</p>
          <p class="text-gray-400 text-xs mt-1">Supports multiple images</p>
          <input type="file" id="fileInput" name="images[]" multiple accept="image/*" class="hidden">
        </div>

        <!-- CAMERA DROPZONE CARD -->
        <div id="cameraDropzoneBtn"
             class="flex-1 min-w-[220px] p-6 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 7l3-3m0 0l3 3m0 0v14m0 0H6m6 0h6"/>
          </svg>
          <p class="text-gray-700 font-semibold">Capture from Camera</p>
          <p class="text-gray-400 text-xs mt-1">Click to open camera</p>
        </div>
      </div>

      <!-- IMAGE PREVIEW CONTAINER -->
      <div id="imagePreviewContainer" class="flex flex-wrap gap-2 mt-4"></div>

      <div>
        <label class="block text-sm font-medium">Remarks</label>
        <textarea name="remarks" class="w-full p-3 border rounded-lg"></textarea>
      </div>

      <button type="submit" class="px-5 py-3 rounded-lg bg-green-500 hover:bg-green-600 text-white font-semibold">
        Save Pickup
      </button>
    </form>
  </section>

  <!-- CAMERA MODAL -->
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

  <script>
    // ---------- DROPZONE LOGIC ----------
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('fileInput');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');

    dropzone.addEventListener('click', () => fileInput.click());

    dropzone.addEventListener('dragover', (e) => {
      e.preventDefault();
      dropzone.classList.add('border-blue-400', 'bg-blue-50');
    });

    dropzone.addEventListener('dragleave', () => {
      dropzone.classList.remove('border-blue-400', 'bg-blue-50');
    });

    dropzone.addEventListener('drop', (e) => {
      e.preventDefault();
      dropzone.classList.remove('border-blue-400', 'bg-blue-50');
      handleFiles(Array.from(e.dataTransfer.files));
    });

    fileInput.addEventListener('change', (e) => handleFiles(Array.from(e.target.files)));

    function handleFiles(files) {
      files.forEach(file => {
        const reader = new FileReader();
        reader.onload = (ev) => addImageToPreview(ev.target.result);
        reader.readAsDataURL(file);
      });
    }

    function addImageToPreview(src) {
      const wrapper = document.createElement('div');
      wrapper.className = 'relative group';

      const img = document.createElement('img');
      img.src = src;
      img.className = 'h-32 w-auto rounded-md border shadow-sm';

      const removeBtn = document.createElement('button');
      removeBtn.innerHTML = '✕';
      removeBtn.className = 'absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition';
      removeBtn.addEventListener('click', () => wrapper.remove());

      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'camera_images[]';
      input.value = src;

      wrapper.appendChild(img);
      wrapper.appendChild(removeBtn);
      wrapper.appendChild(input);
      imagePreviewContainer.appendChild(wrapper);
    }

    // ---------- CAMERA LOGIC ----------
    let stream;
    const cameraDropzoneBtn = document.getElementById('cameraDropzoneBtn');
    const cameraModal = document.getElementById('cameraModal');
    const video = document.getElementById('cameraVideo');
    const canvas = document.createElement('canvas');

    cameraDropzoneBtn.addEventListener('click', async () => {
      cameraModal.classList.remove('hidden');
      try {
        stream = await navigator.mediaDevices.getUserMedia({video: {facingMode: 'environment'}});
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
      addImageToPreview(imageDataUrl);
      closeCamera();
    });

    function closeCamera() {
      cameraModal.classList.add('hidden');
      if (stream) stream.getTracks().forEach(track => track.stop());
    }
  </script>
@endsection
