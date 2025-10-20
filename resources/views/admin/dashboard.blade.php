@extends('layouts/contentNavbarLayout')

@section('title', 'Admin Dashboard')

@section('content')
  <div class="container-fluid">
    {{-- Welcome Header --}}
    <div class="row mb-4">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h2 class="mb-1 fw-bold">Welcome Back! 👋</h2>
            <p class="text-muted mb-0">Here's what's happening with your business today</p>
          </div>
          <div class="text-end">
            <p class="mb-0 text-muted small">{{ date('l, F j, Y') }}</p>
            <p class="mb-0 fw-semibold">{{ date('g:i A') }}</p>
          </div>
        </div>
      </div>
    </div>

    {{-- Quick Status Banner --}}
    <div class="row mb-4">
      <div class="col-12">
        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
          <div class="card-body py-3">
            <div class="row align-items-center text-white">
              <div class="col-md-3 col-6 text-center border-end border-white-50">
                <div class="d-flex align-items-center justify-content-center">
                  <i class="bx bx-check-circle fs-3 me-2"></i>
                  <div class="text-start">
                    <h3 class="mb-0 text-white fw-bold">{{ $onlineEmployees }}</h3>
                    <small class="opacity-75">Online Now</small>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-6 text-center border-end border-white-50">
                <div class="d-flex align-items-center justify-content-center">
                  <i class="bx bx-time-five fs-3 me-2"></i>
                  <div class="text-start">
                    <h3 class="mb-0 text-white fw-bold">{{ $awayEmployees }}</h3>
                    <small class="opacity-75">Away</small>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-6 text-center border-end border-white-50 mt-3 mt-md-0">
                <div class="d-flex align-items-center justify-content-center">
                  <i class="bx bx-user-check fs-3 me-2"></i>
                  <div class="text-start">
                    <h3 class="mb-0 text-white fw-bold">{{ $todayEmployeesIn }}</h3>
                    <small class="opacity-75">Marked In Today</small>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-6 text-center mt-3 mt-md-0">
                <div class="d-flex align-items-center justify-content-center">
                  <i class="bx bx-trending-up fs-3 me-2"></i>
                  <div class="text-start">
                    <h3 class="mb-0 text-white fw-bold">{{ $totalEmployees }}</h3>
                    <small class="opacity-75">Total Staff</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Today's Performance --}}
    <div class="row mb-4">
      <div class="col-12">
        <div class="d-flex align-items-center mb-3">
          <div class="badge bg-label-primary me-2">TODAY</div>
          <h4 class="mb-0 fw-bold">Today's Performance</h4>
        </div>
      </div>
    </div>

    <div class="row g-3 mb-5">
      <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm hover-lift h-100">
          <div class="card-body">
            <div class="d-flex align-items-start justify-content-between">
              <div class="flex-grow-1">
                <p class="text-muted mb-2 small fw-semibold">HOURS TODAY</p>
                <h3 class="mb-0 fw-bold text-primary">{{ $todayHours }}<small class="fs-6 text-muted"> hrs</small></h3>
                <small class="text-success"><i class="bx bx-up-arrow-alt"></i> Active time</small>
              </div>
              <div class="avatar flex-shrink-0">
                <div class="avatar-initial rounded" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                  <i class="bx bx-time-five text-white fs-4"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm hover-lift h-100">
          <div class="card-body">
            <div class="d-flex align-items-start justify-content-between">
              <div class="flex-grow-1">
                <p class="text-muted mb-2 small fw-semibold">EARNINGS TODAY</p>
                <h3 class="mb-0 fw-bold text-success">£ {{ number_format($todayEarnings,2) }}</h3>
                <small class="text-success"><i class="bx bx-up-arrow-alt"></i> Revenue</small>
              </div>
              <div class="avatar flex-shrink-0">
                <div class="avatar-initial rounded" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                  <i class="bx bx-dollar-circle text-white fs-4"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm hover-lift h-100">
          <div class="card-body">
            <div class="d-flex align-items-start justify-content-between">
              <div class="flex-grow-1">
                <p class="text-muted mb-2 small fw-semibold">PICKUPS TODAY</p>
                <h3 class="mb-0 fw-bold text-warning">{{ $todayPickups }}</h3>
                <small class="text-success"><i class="bx bx-up-arrow-alt"></i> Completed</small>
              </div>
              <div class="avatar flex-shrink-0">
                <div class="avatar-initial rounded" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                  <i class="bx bx-package text-white fs-4"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm hover-lift h-100">
          <div class="card-body">
            <div class="d-flex align-items-start justify-content-between">
              <div class="flex-grow-1">
                <p class="text-muted mb-2 small fw-semibold">DROPS TODAY</p>
                <h3 class="mb-0 fw-bold text-info">{{ $todayDrops }}</h3>
                <small class="text-success"><i class="bx bx-up-arrow-alt"></i> Delivered</small>
              </div>
              <div class="avatar flex-shrink-0">
                <div class="avatar-initial rounded" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                  <i class="bx bx-box text-white fs-4"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Overall Statistics --}}
    <div class="row mb-4">
      <div class="col-12">
        <div class="d-flex align-items-center mb-3">
          <div class="badge bg-label-success me-2">ALL TIME</div>
          <h4 class="mb-0 fw-bold">Overall Statistics</h4>
        </div>
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-xl-4 col-md-6">
        <div class="card border-0 shadow-sm hover-lift h-100">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-8">
                <p class="text-muted mb-2 small fw-semibold">TOTAL EMPLOYEES</p>
                <h2 class="mb-0 fw-bold">{{ $totalEmployees }}</h2>
                <div class="progress mt-3" style="height: 6px;">
                  <div class="progress-bar bg-gradient" role="progressbar" style="width: 75%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
              <div class="col-4 text-end">
                <div class="avatar avatar-xl">
                  <div class="avatar-initial rounded-circle" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="bx bx-group text-white fs-1"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-md-6">
        <div class="card border-0 shadow-sm hover-lift h-100">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-8">
                <p class="text-muted mb-2 small fw-semibold">TOTAL HOURS</p>
                <h2 class="mb-0 fw-bold">{{ $totalHours }}<small class="fs-5"> hrs</small></h2>
                <div class="progress mt-3" style="height: 6px;">
                  <div class="progress-bar bg-gradient" role="progressbar" style="width: 65%; background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
              <div class="col-4 text-end">
                <div class="avatar avatar-xl">
                  <div class="avatar-initial rounded-circle" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <i class="bx bx-time text-white fs-1"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-md-6">
        <div class="card border-0 shadow-sm hover-lift h-100">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-8">
                <p class="text-muted mb-2 small fw-semibold">TOTAL EARNINGS</p>
                <h2 class="mb-0 fw-bold text-success">£ {{ number_format($totalEarnings,2) }}</h2>
                <div class="progress mt-3" style="height: 6px;">
                  <div class="progress-bar bg-gradient" role="progressbar" style="width: 85%; background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
              <div class="col-4 text-end">
                <div class="avatar avatar-xl">
                  <div class="avatar-initial rounded-circle" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <i class="bx bx-money text-white fs-1"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-6 col-md-6">
        <div class="card border-0 shadow-sm hover-lift h-100">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-8">
                <p class="text-muted mb-2 small fw-semibold">TOTAL PICKUPS</p>
                <h2 class="mb-0 fw-bold text-warning">{{ $totalPickups }}</h2>
                <div class="progress mt-3" style="height: 6px;">
                  <div class="progress-bar bg-gradient" role="progressbar" style="width: 70%; background: linear-gradient(90deg, #fa709a 0%, #fee140 100%);" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
              <div class="col-4 text-end">
                <div class="avatar avatar-xl">
                  <div class="avatar-initial rounded-circle" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <i class="bx bx-upload text-white fs-1"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-6 col-md-6">
        <div class="card border-0 shadow-sm hover-lift h-100">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-8">
                <p class="text-muted mb-2 small fw-semibold">TOTAL DROPS</p>
                <h2 class="mb-0 fw-bold text-danger">{{ $totalDrops }}</h2>
                <div class="progress mt-3" style="height: 6px;">
                  <div class="progress-bar bg-gradient" role="progressbar" style="width: 80%; background: linear-gradient(90deg, #ff6b6b 0%, #ee5a6f 100%);" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
              <div class="col-4 text-end">
                <div class="avatar avatar-xl">
                  <div class="avatar-initial rounded-circle" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);">
                    <i class="bx bx-download text-white fs-1"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    .hover-lift {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }

    .card {
      border-radius: 12px;
    }

    .avatar-initial {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .badge {
      padding: 4px 10px;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    .progress {
      border-radius: 10px;
      background-color: #f0f0f0;
    }

    .progress-bar {
      border-radius: 10px;
    }
  </style>
@endsection
