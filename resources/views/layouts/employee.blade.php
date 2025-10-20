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

    .active-nav {
      box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.5);
      transform: scale(1.02);
    }

    /* Mobile menu animation */
    .mobile-menu {
      transform: translateX(-100%);
      transition: transform 0.3s ease-in-out;
    }

    .mobile-menu.active {
      transform: translateX(0);
    }

    /* Overlay for mobile menu */
    .menu-overlay {
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s ease-in-out;
    }

    .menu-overlay.active {
      opacity: 1;
      pointer-events: auto;
    }

    /* Hide scrollbar when menu is open */
    body.menu-open {
      overflow: hidden;
    }

    /* Hamburger animation */
    .hamburger-line {
      transition: all 0.3s ease;
    }

    .hamburger.active .hamburger-line:nth-child(1) {
      transform: rotate(45deg) translate(6px, 6px);
    }

    .hamburger.active .hamburger-line:nth-child(2) {
      opacity: 0;
    }

    .hamburger.active .hamburger-line:nth-child(3) {
      transform: rotate(-45deg) translate(6px, -6px);
    }

    /* Mobile menu animation */
    .mobile-menu {
      transform: translateX(-100%);
      transition: transform 0.3s ease-in-out;
    }

    /* Active state */
    .mobile-menu.active {
      transform: translateX(0);
    }

    /* Desktop: always show sidebar */
    @media (min-width: 1024px) {
      .mobile-menu {
        transform: translateX(0) !important;
        position: static !important;
        height: auto;
        width: 20%;
        top: auto;
        left: auto;
      }

      .menu-overlay {
        display: none;
      }
    }

  </style>
  @laravelPWA
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-100 via-purple-100 to-pink-100 text-slate-800">
<div class="w-full">
  <!-- Header -->
  <header class="bg-white shadow p-4 flex items-center justify-between gap-3 sticky top-0 z-50">
    <!-- Mobile Menu Button -->
    <button id="mobileMenuBtn" class="lg:hidden p-2 rounded-lg hover:bg-slate-100 transition-colors hamburger">
      <div class="w-6 h-0.5 bg-slate-700 hamburger-line mb-1.5"></div>
      <div class="w-6 h-0.5 bg-slate-700 hamburger-line mb-1.5"></div>
      <div class="w-6 h-0.5 bg-slate-700 hamburger-line"></div>
    </button>

    <!-- Logo and Title -->
    <div class="flex items-center gap-3 flex-1 lg:flex-initial">
      <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-indigo-400 to-pink-500 flex items-center justify-center text-white font-bold text-sm sm:text-lg">
        TS
      </div>
      <div class="hidden sm:block">
        <h1 class="text-base sm:text-lg font-semibold">Employee Dashboard</h1>
        <p class="text-xs text-slate-600">Pickup & Drop | Attendance | Earnings</p>
      </div>
      <div class="sm:hidden">
        <h1 class="text-sm font-semibold">TapShift</h1>
      </div>
    </div>

    <!-- Header Right Section -->
    <div class="flex items-center gap-2 sm:gap-4">
      <div class="text-right hidden sm:block">
        <div class="text-xs text-slate-500">Today</div>
        <div id="todayDate" class="font-medium text-slate-700 text-sm">{{Carbon\Carbon::now()->format('Y M d, h:i A')}}</div>
      </div>
      <form action="{{ route('employee.logout') }}" method="POST">
        @csrf
        <button type="submit" class="px-2 py-1.5 sm:px-3 sm:py-2 bg-red-500 hover:bg-red-600 text-white rounded text-sm transition-colors">
          Logout
        </button>
      </form>
    </div>
  </header>

  <!-- Mobile Menu Overlay -->
  <div id="menuOverlay" class="menu-overlay fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

  <div class="flex flex-col lg:flex-row w-full">
    <!-- Sidebar -->
    <aside id="sidebar" class="mobile-menu fixed lg:static top-0 left-0 h-screen lg:w-1/5 w-72 bg-white shadow-2xl lg:shadow-lg p-5 flex flex-col gap-5 z-50 overflow-y-auto">
      <!-- Close button for mobile -->
      <button id="closeMobileMenu" class="lg:hidden absolute top-4 right-4 p-2 rounded-lg hover:bg-slate-100">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>

      <!-- Profile Section -->
      <div class="flex items-center gap-3 border-b border-slate-200 pb-4 pr-8">
        @php
          $employee = \Illuminate\Support\Facades\Auth::guard('employee')->user();
          $profileImage = $employee && $employee->image
              ? asset('storage/' . $employee->image)
              : asset('images/default-avatar.png');
        @endphp

        <img src="{{ $profileImage }}" id="profileAvatar" alt="avatar"
             class="w-12 h-12 rounded-full object-cover border bg-slate-100 flex-shrink-0"/>

        <div class="overflow-hidden">
          <div class="font-medium text-slate-800 truncate">{{ $employee->name }}</div>
          <div class="text-xs text-slate-500 truncate">{{ $employee->email }}</div>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="flex flex-col gap-2">
        <a href="{{ route('employee.dashboard') }}" class="navBtn w-full px-4 py-3 rounded-lg nav-gradient-1 text-white font-medium shadow {{ request()->routeIs('employee.dashboard') ? 'active-nav' : '' }} transition-all hover:scale-105">
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>Dashboard</span>
          </div>
        </a>

        <a href="{{ route('employee.pickup') }}" class="navBtn w-full px-4 py-3 rounded-lg nav-gradient-2 text-white font-medium shadow {{ request()->routeIs('employee.pickup') ? 'active-nav' : '' }} transition-all hover:scale-105">
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <span>Pickup Entry</span>
          </div>
        </a>

        <a href="{{ route('employee.drop') }}" class="navBtn w-full px-4 py-3 rounded-lg nav-gradient-3 text-white font-medium shadow {{ request()->routeIs('employee.drop') ? 'active-nav' : '' }} transition-all hover:scale-105">
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Drop Entry</span>
          </div>
        </a>

        <a href="{{ route('employee.attendance') }}" class="navBtn w-full px-4 py-3 rounded-lg nav-gradient-4 text-white font-medium shadow {{ request()->routeIs('employee.attendance') ? 'active-nav' : '' }} transition-all hover:scale-105">
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span>Attendance Report</span>
          </div>
        </a>

        <a href="{{ route('employee.pdreport') }}" class="navBtn w-full px-4 py-3 rounded-lg nav-gradient-5 text-white font-medium shadow {{ request()->routeIs('employee.pdreport') ? 'active-nav' : '' }} transition-all hover:scale-105">
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Pickup/Drop Report</span>
          </div>
        </a>

        <a href="{{ route('employee.earnings') }}" class="navBtn w-full px-4 py-3 rounded-lg bg-indigo-500 text-white font-medium shadow {{ request()->routeIs('employee.earnings') ? 'active-nav' : '' }} transition-all hover:scale-105">
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Earnings Report</span>
          </div>
        </a>

        <a href="{{ route('employee.profile.edit') }}" class="navBtn w-full px-4 py-3 rounded-lg bg-slate-200 text-slate-800 font-medium shadow {{ request()->routeIs('employee.documents') ? 'active-nav' : '' }} transition-all hover:scale-105">
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span>Employee Profile</span>
          </div>
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="lg:w-4/5 w-full p-4 sm:p-6 space-y-4 sm:space-y-6">
      <!-- Success/Error Messages -->
      @if(session('success'))
        <div class="p-3 mb-4 bg-green-100 text-green-800 rounded text-sm sm:text-base">{{ session('success') }}</div>
      @endif

      @if(session('error'))
        <div class="p-3 mb-4 bg-red-100 text-red-800 rounded text-sm sm:text-base">{{ session('error') }}</div>
      @endif

      @yield('content')
    </main>
  </div>
</div>

<script>
  // Mobile menu functionality
  const mobileMenuBtn = document.getElementById('mobileMenuBtn');
  const closeMobileMenu = document.getElementById('closeMobileMenu');
  const sidebar = document.getElementById('sidebar');
  const menuOverlay = document.getElementById('menuOverlay');
  const hamburger = document.querySelector('.hamburger');

  function openMenu() {
    sidebar.classList.add('active');
    menuOverlay.classList.add('active');
    hamburger.classList.add('active');
    document.body.classList.add('menu-open');
  }

  function closeMenu() {
    sidebar.classList.remove('active');
    menuOverlay.classList.remove('active');
    hamburger.classList.remove('active');
    document.body.classList.remove('menu-open');
  }

  mobileMenuBtn.addEventListener('click', openMenu);
  closeMobileMenu.addEventListener('click', closeMenu);
  menuOverlay.addEventListener('click', closeMenu);

  // Close menu when clicking on nav links on mobile
  const navLinks = document.querySelectorAll('.navBtn');
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth < 1024) {
        closeMenu();
      }
    });
  });

  // Close menu on window resize if opened
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
      closeMenu();
    }
  });

  // Update date time every second
  function updateDateTime() {
    const now = new Date();
    const options = {
      year: 'numeric',
      month: 'short',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit',
      hour12: true
    };
    const dateStr = now.toLocaleString('en-US', options);
    const dateElement = document.getElementById('todayDate');
    if (dateElement) {
      dateElement.textContent = dateStr;
    }
  }

  // Update every second
  setInterval(updateDateTime, 1000);
  updateDateTime();
</script>
</body>
</html>
