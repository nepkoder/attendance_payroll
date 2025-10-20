@extends('layouts.employee')
@section('content')
  <section class="glass rounded-2xl p-6 shadow-md">
    <div class="flex justify-between items-start">
      <h2 class="text-lg font-semibold">Dashboard</h2>
      <div class="text-sm text-slate-600">Quick overview</div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-4">
      <div class="p-4 rounded-xl bg-white shadow text-center card-animated">
        <div class="text-sm text-slate-500">Total Hours</div>
        <div id="totalHours" class="text-2xl font-bold">
          {{ $totalHoursDecimal }} h
        </div>

        {{--        <div id="totalHours" class="text-2xl font-bold">--}}
{{--          @php--}}
{{--            $parts = [];--}}
{{--            if($totalTime['days'] ?? 0) $parts[] = $totalTime['days'].'d';--}}
{{--            if($totalTime['hours'] ?? 0) $parts[] = $totalTime['hours'].'h';--}}
{{--            if($totalTime['minutes'] ?? 0) $parts[] = $totalTime['minutes'].'m';--}}
{{--            if($totalTime['seconds'] ?? 0) $parts[] = $totalTime['seconds'].'s';--}}
{{--          @endphp--}}
{{--          {{ $parts ? implode(' ', $parts) : '0h' }}--}}
{{--        </div>--}}
      </div>

      <div class="p-4 rounded-xl bg-white shadow text-center card-animated">
        <div class="text-sm text-slate-500">Total Pickups</div>
        <div id="totalPickups" class="text-2xl font-bold">{{$totalPickups ?? 0}}</div>
      </div>

      <div class="p-4 rounded-xl bg-white shadow text-center card-animated">
        <div class="text-sm text-slate-500">Total Drops</div>
        <div id="totalDrops" class="text-2xl font-bold">{{$totalDrops ?? 0}}</div>
      </div>

      <div class="p-4 rounded-xl bg-white shadow text-center card-animated">
        <div class="text-sm text-slate-500">Total Earnings</div>
        <div id="totalEarnings" class="text-2xl font-bold">£ {{ number_format($totalEarnings, 2) }}</div>
      </div>
    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="p-4 rounded-lg bg-white shadow card-animated">
        <div class="text-sm text-slate-500">Today's Pickups</div>
        <div id="todayPickups" class="text-xl font-semibold">{{$todaysPickups}}</div>
      </div>
      <div class="p-4 rounded-lg bg-white shadow card-animated">
        <div class="text-sm text-slate-500">Today's Drops</div>
        <div id="todayDrops" class="text-xl font-semibold">{{$todaysDrops}}</div>
      </div>
      <div class="p-4 rounded-lg bg-white shadow card-animated">
        <div class="text-sm text-slate-500">Today's Earnings</div>
        <div id="todayEarnings" class="text-xl font-semibold">£ {{ number_format($todayEarnings, 2) }}</div>
      </div>
    </div>

    <div class="mt-6">
      <h3 class="text-base font-semibold mb-2">Mark Attendance</h3>
      <div class="flex flex-wrap gap-3">
        <button
          id="markInBtn"
          class="flex-1 sm:flex-none px-6 py-3 rounded-lg
           bg-green-500 hover:bg-green-600 text-white font-semibold shadow
           {{ $sessionStatus === 'running' ? 'opacity-60 cursor-not-allowed' : '' }}"
          {{ $sessionStatus === 'running' ? 'disabled' : '' }}
        >
          Mark In
        </button>

        <button
          id="markOutBtn"
          class="flex-1 sm:flex-none px-6 py-3 rounded-lg
           bg-red-500 hover:bg-red-600 text-white font-semibold shadow
           {{ $sessionStatus !== 'running' ? 'opacity-60 cursor-not-allowed' : '' }}"
          {{ $sessionStatus !== 'running' ? 'disabled' : '' }}
        >
          Mark Out
        </button>

      </div>

      <div id="attendanceMessage" class="mt-3 text-sm text-slate-700"></div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
        <div class="p-3 rounded-lg bg-white/60 shadow">
          <div class="text-xs text-slate-500">Marked In</div>
          <div id="markedInAt"
               class="font-medium text-slate-800">{{ $markinTime ? \Carbon\Carbon::parse($markinTime)->format('d M Y H:i:s') : '-' }}</div>
        </div>
        <div class="p-3 rounded-lg bg-white/60 shadow">
          <div class="text-xs text-slate-500">Marked Out</div>
          <div id="markedOutAt"
               class="font-medium text-slate-800">{{ $markoutTime ? \Carbon\Carbon::parse($markoutTime)->format('d M Y H:i:s') : '-' }}</div>
        </div>
        <div class="p-3 rounded-lg bg-white/60 shadow">
          <div class="text-xs text-slate-500">Session Hours</div>
          <div id="sessionHours" class="text-lg font-semibold text-slate-900">
            @if($sessionStatus === 'completed')
              @php
                $seconds = \Carbon\Carbon::parse($markinTime)->diffInSeconds(\Carbon\Carbon::parse($markoutTime));
                $days = floor($seconds / 86400);
                $hours = floor(($seconds % 86400) / 3600);
                $minutes = floor(($seconds % 3600) / 60);
                $secs = $seconds % 60;
                $parts = [];
                if($days) $parts[] = $days.'d';
                if($hours) $parts[] = $hours.'h';
                if($minutes) $parts[] = $minutes.'m';
                if($secs) $parts[] = $secs.'s';
              @endphp
              {{ implode(' ', $parts) }}
            @else
              <span id="runningSession">-</span>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const messageDiv = document.getElementById('attendanceMessage');

    @if($sessionStatus === 'running')
    function updateSessionTime() {
      const runningSpan = document.getElementById('runningSession');
      if (!runningSpan) return; // safety check

      const markIn = new Date("{{ $markinTime }}").getTime();
      const now = new Date().getTime();
      let diff = Math.floor((now - markIn) / 1000); // seconds

      let days = Math.floor(diff / 86400);
      let hours = Math.floor((diff % 86400) / 3600);
      let minutes = Math.floor((diff % 3600) / 60);
      let seconds = diff % 60;

      let parts = [];
      if (days) parts.push(days + 'd');
      if (hours) parts.push(hours + 'h');
      if (minutes) parts.push(minutes + 'm');
      if (seconds) parts.push(seconds + 's');

      runningSpan.textContent = parts.join(' ') || '0h';
    }

    setInterval(updateSessionTime, 1000);
    updateSessionTime();
    @endif

    const getLocationAndSend = async (url) => {
      // Show loading message
      messageDiv.textContent = 'Getting your location...';
      messageDiv.style.color = 'blue';

      // Check if geolocation is supported
      if (!navigator.geolocation) {
        messageDiv.textContent = 'Geolocation is not supported by your browser.';
        messageDiv.style.color = 'red';
        return;
      }

      try {
        // Get current position with Promise
        messageDiv.textContent = 'Requesting location permission...';

        const position = await new Promise((resolve, reject) => {
          navigator.geolocation.getCurrentPosition(resolve, reject, {
            enableHighAccuracy: false,
            timeout: 20000, // Increased timeout
            maximumAge: 30000
          });
        });

        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;

        // Show sending message
        messageDiv.textContent = 'Location received. Marking attendance...';
        messageDiv.style.color = 'blue';

        // Send data
        await sendAttendanceData(url, latitude, longitude);

      } catch (error) {

        console.log(error.message)

        // Handle specific error types
        if (error.code === 1) {
          messageDiv.textContent = 'Location permission denied. Please enable location access to mark attendance.';
        } else if (error.code === 2) {
          messageDiv.textContent = 'Location unavailable. Please check your device settings.';
        } else if (error.code === 3) {
          messageDiv.textContent = 'Location request timed out. Please try again.';
        } else {
          messageDiv.textContent = 'Failed to get location. Please enable location and try again.';
        }
        messageDiv.style.color = 'red';
        // await sendAttendanceData(url, 10, 10);

      }
    };

    const sendAttendanceData = async (url, lat, lng) => {
      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({latitude: lat, longitude: lng})
        });

        const data = await response.json();

        if (!response.ok) {
          messageDiv.textContent = data.error || 'Failed to mark attendance.';
          messageDiv.style.color = 'red';
          return;
        }

        messageDiv.textContent = data.message || 'Attendance marked successfully!';
        messageDiv.style.color = 'green';

        // Reload after showing success message
        setTimeout(() => location.reload(), 1500);
      } catch (err) {
        console.error(err);
        messageDiv.textContent = 'Network error. Please check your connection.';
        messageDiv.style.color = 'red';
      }
    };

// Attach click events
    document.getElementById('markInBtn')?.addEventListener('click', () => {
      getLocationAndSend('{{ route("employee.attendance.markIn") }}');
    });
    document.getElementById('markOutBtn')?.addEventListener('click', () => {
      getLocationAndSend('{{ route("employee.attendance.markOut") }}');
    });
  });
</script>
