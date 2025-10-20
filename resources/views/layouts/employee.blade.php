<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Employee Dashboard — Pickup / Drop</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/tesseract.js@v2.1.5/dist/tesseract.min.js"></script>
  <style>
    .nav-gradient-1 {
      background: linear-gradient(180deg, #3b82f6, #60a5fa);
    }

    .nav-gradient-2 {
      background: linear-gradient(180deg, #8b5cf6, #a78bfa);
    }

    .nav-gradient-3 {
      background: linear-gradient(180deg, #f59e0b, #fcd34d);
    }

    .nav-gradient-4 {
      background: linear-gradient(180deg, #10b981, #6ee7b7);
    }

    .nav-gradient-5 {
      background: linear-gradient(180deg, #ef4444, #fb7185);
    }

    .glass {
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.4);
    }

    .small {
      font-size: 0.85rem
    }

    .card-animated {
      transition: transform .18s ease, box-shadow .18s ease;
    }

    .card-animated:hover {
      transform: translateY(-6px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-100 via-purple-100 to-pink-100 text-slate-800">
<div class="w-full">
  <header
    class="bg-white shadow p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sticky top-0 z-50">
    <div class="flex items-center gap-3">
      <div
        class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-400 to-pink-500 flex items-center justify-center text-white font-bold text-lg">
        ER
      </div>
      <div>
        <h1 class="text-lg font-semibold">Employee Dashboard</h1>
        <p class="text-xs text-slate-600">Pickup & Drop | Attendance | Earnings</p>
      </div>
    </div>
    <div class="flex items-center gap-4">
      <div class="text-right">
        <div class="text-xs text-slate-500">Today</div>
        <div id="todayDate" class="font-medium text-slate-700">{{\Carbon\Carbon::now()->format('Y M d, h:i A')}}</div>
      </div>
      <form action="{{ route('employee.logout') }}" method="POST">
        @csrf
        <button type="submit" class="px-3 py-2 bg-red-500 text-white rounded">
          Logout
        </button>
      </form>

    </div>
  </header>

  <div class="flex flex-col lg:flex-row w-full">
    <!-- Sidebar -->
    <aside class="lg:w-1/5 w-full bg-white/80 shadow-lg lg:h-screen p-5 flex flex-col gap-5 sticky top-[72px]">
      <div class="flex items-center gap-3 border-b border-slate-200 pb-4">
        @php
          $employee = \Illuminate\Support\Facades\Auth::guard('employee')->user();
          $profileImage = $employee && $employee->image
              ? asset('storage/' . $employee->image)
              : asset('images/default-avatar.png');
        @endphp

        <img src="{{ $profileImage }}" id="profileAvatar" alt="avatar"
             class="w-12 h-12 rounded-full object-cover border bg-slate-100"/>

        <div>
          <div class="font-medium text-slate-800">{{ $employee->name }}</div>
          <div class="text-xs text-slate-500">{{ $employee->email }}</div>
        </div>
      </div>

      <nav class="flex flex-col gap-2">
        <a href="{{ route('employee.dashboard') }}" class="navBtn w-full px-4 py-2 rounded-lg nav-gradient-1 text-white font-medium shadow {{ request()->routeIs('employee.dashboard') ? 'active-nav' : '' }}">Dashboard</a>

        <a href="{{ route('employee.pickup') }}" class="navBtn w-full px-4 py-2 rounded-lg nav-gradient-2 text-white font-medium shadow {{ request()->routeIs('employee.pickup') ? 'active-nav' : '' }}">Pickup Entry</a>

        <a href="{{ route('employee.drop') }}" class="navBtn w-full px-4 py-2 rounded-lg nav-gradient-3 text-white font-medium shadow {{ request()->routeIs('employee.drop') ? 'active-nav' : '' }}">Drop Entry</a>

        <a href="{{ route('employee.attendance') }}" class="navBtn w-full px-4 py-2 rounded-lg nav-gradient-4 text-white font-medium shadow {{ request()->routeIs('employee.attendance') ? 'active-nav' : '' }}">Attendance Report</a>

        <a href="{{ route('employee.pdreport') }}" class="navBtn w-full px-4 py-2 rounded-lg nav-gradient-5 text-white font-medium shadow {{ request()->routeIs('employee.pdreport') ? 'active-nav' : '' }}">Pickup/Drop Report</a>

        <a href="{{ route('employee.earnings') }}" class="navBtn w-full px-4 py-2 rounded-lg bg-indigo-500 text-white font-medium shadow {{ request()->routeIs('employee.earnings') ? 'active-nav' : '' }}">Earnings Report</a>

        <a href="{{ route('employee.profile.edit') }}" class="navBtn w-full px-4 py-2 rounded-lg bg-slate-200 text-slate-800 font-medium shadow {{ request()->routeIs('employee.documents') ? 'active-nav' : '' }}">Employee Profile</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="lg:w-4/5 w-full p-6 space-y-6">

      <!-- Pickup Entry -->
      @if(session('success'))
        <div class="p-3 mb-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
      @endif

      @if(session('error'))
        <div class="p-3 mb-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
      @endif

      @yield('content')

    </main>
  </div>
</div>
</body>
</html>
