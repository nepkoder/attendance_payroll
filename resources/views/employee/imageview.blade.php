<!-- Pickup Image Modal -->
<div id="pickupModal" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-50">
  <button onclick="closePickupModal()"
          class="absolute top-4 right-4 text-white bg-red-600 hover:bg-red-700 rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg z-50">
    ✕
  </button>

  <div class="relative max-w-4xl w-full bg-white rounded-lg overflow-hidden p-4 flex flex-col items-center">
    <img id="pickupMainImage" src="" alt="Pickup Image" class="max-h-[75vh] w-auto rounded-lg shadow-lg mb-4">

    <div class="flex justify-between items-center w-full mt-2">
      <button id="pickupPrevBtn"
              class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg font-semibold text-slate-700">
        ◀ Prev
      </button>
      <div id="pickupImageCounter" class="text-slate-600 text-sm"></div>
      <button id="pickupNextBtn"
              class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg font-semibold text-slate-700">
        Next ▶
      </button>
    </div>

    <div id="pickupThumbnailContainer" class="flex gap-2 mt-4 overflow-x-auto max-w-full p-2"></div>
  </div>
</div>

<!-- Drop Image Modal -->
<div id="dropModal" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-50">
  <button onclick="closeDropModal()"
          class="absolute top-4 right-4 text-white bg-red-600 hover:bg-red-700 rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg z-50">
    ✕
  </button>

  <div class="relative max-w-4xl w-full bg-white rounded-lg overflow-hidden p-4 flex flex-col items-center">
    <img id="dropMainImage" src="" alt="Drop Image" class="max-h-[75vh] w-auto rounded-lg shadow-lg mb-4">

    <div class="flex justify-between items-center w-full mt-2">
      <button id="dropPrevBtn"
              class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg font-semibold text-slate-700">
        ◀ Prev
      </button>
      <div id="dropImageCounter" class="text-slate-600 text-sm"></div>
      <button id="dropNextBtn"
              class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg font-semibold text-slate-700">
        Next ▶
      </button>
    </div>

    <div id="dropThumbnailContainer" class="flex gap-2 mt-4 overflow-x-auto max-w-full p-2"></div>
  </div>
</div>

<style>
  #pickupMainImage, #dropMainImage {
    transition: opacity 0.3s ease-in-out;
  }
</style>

<script>
  // Pickup Modal
  let pickupIndex = 0;
  let pickupImages = [];

  function openPickupModal(images) {
    pickupImages = images.map(img => '/storage/' + img);
    pickupIndex = 0;
    showPickupImage(pickupIndex);

    const container = document.getElementById('pickupThumbnailContainer');
    container.innerHTML = '';
    pickupImages.forEach((src, idx) => {
      const thumb = document.createElement('img');
      thumb.src = src;
      thumb.className = 'h-16 w-auto rounded-md border cursor-pointer hover:opacity-80 transition';
      thumb.onclick = () => showPickupImage(idx);
      container.appendChild(thumb);
    });

    document.getElementById('pickupModal').classList.remove('hidden');
  }

  function showPickupImage(idx) {
    if (idx < 0) idx = pickupImages.length - 1;
    if (idx >= pickupImages.length) idx = 0;
    pickupIndex = idx;

    const imgEl = document.getElementById('pickupMainImage');
    imgEl.style.opacity = 0;
    setTimeout(() => {
      imgEl.src = pickupImages[pickupIndex];
      imgEl.onload = () => imgEl.style.opacity = 1;
    }, 150);

    document.getElementById('pickupImageCounter').textContent = `Image ${pickupIndex + 1} of ${pickupImages.length}`;
  }

  function closePickupModal() {
    document.getElementById('pickupModal').classList.add('hidden');
  }

  document.getElementById('pickupPrevBtn').addEventListener('click', () => showPickupImage(pickupIndex - 1));
  document.getElementById('pickupNextBtn').addEventListener('click', () => showPickupImage(pickupIndex + 1));

  // Drop Modal
  let dropIndex = 0;
  let dropImages = [];

  function openDropModal(images) {
    dropImages = images.map(img => '/storage/' + img);
    dropIndex = 0;
    showDropImage(dropIndex);

    const container = document.getElementById('dropThumbnailContainer');
    container.innerHTML = '';
    dropImages.forEach((src, idx) => {
      const thumb = document.createElement('img');
      thumb.src = src;
      thumb.className = 'h-16 w-auto rounded-md border cursor-pointer hover:opacity-80 transition';
      thumb.onclick = () => showDropImage(idx);
      container.appendChild(thumb);
    });

    document.getElementById('dropModal').classList.remove('hidden');
  }

  function showDropImage(idx) {
    if (idx < 0) idx = dropImages.length - 1;
    if (idx >= dropImages.length) idx = 0;
    dropIndex = idx;

    const imgEl = document.getElementById('dropMainImage');
    imgEl.style.opacity = 0;
    setTimeout(() => {
      imgEl.src = dropImages[dropIndex];
      imgEl.onload = () => imgEl.style.opacity = 1;
    }, 150);

    document.getElementById('dropImageCounter').textContent = `Image ${dropIndex + 1} of ${dropImages.length}`;
  }

  function closeDropModal() {
    document.getElementById('dropModal').classList.add('hidden');
  }

  document.getElementById('dropPrevBtn').addEventListener('click', () => showDropImage(dropIndex - 1));
  document.getElementById('dropNextBtn').addEventListener('click', () => showDropImage(dropIndex + 1));

  // Keyboard navigation for both modals
  document.addEventListener('keydown', (e) => {
    if (!document.getElementById('pickupModal').classList.contains('hidden')) {
      if (e.key === 'ArrowRight') showPickupImage(pickupIndex + 1);
      if (e.key === 'ArrowLeft') showPickupImage(pickupIndex - 1);
      if (e.key === 'Escape') closePickupModal();
    }
    if (!document.getElementById('dropModal').classList.contains('hidden')) {
      if (e.key === 'ArrowRight') showDropImage(dropIndex + 1);
      if (e.key === 'ArrowLeft') showDropImage(dropIndex - 1);
      if (e.key === 'Escape') closeDropModal();
    }
  });
</script>
