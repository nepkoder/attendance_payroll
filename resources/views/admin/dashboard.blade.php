@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('content')
<div class="row">
    <div class="col-xxl-6 mb-6 order-0">
        <div class="card">
            <div class="d-flex align-items-start row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary mb-3">Congratulations {{Auth::guard('web')->user()->name}}! 🎉</h5>
                        <p class="mb-6">You have done 72% more sales today.<br />Check your new badge in your profile.</p>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-6">
                        <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" height="175" alt="View Badge User" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-6 col-lg-12 col-md-4 order-1">
        <div class="row">
            <div class="col-lg-4 col-md-12 col-6 mb-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between mb-4">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded" />
                            </div>
                        </div>
                        <p class="mb-1">Total Employee</p>
                        <h4 class="card-title">{{\App\Models\Employee::count()}}</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-6 mb-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between mb-4">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('assets/img/icons/unicons/wallet-info.png') }}" alt="wallet info" class="rounded" />
                            </div>
                        </div>
                        <p class="mb-1">Total Hours</p>
                        <h4 class="card-title mb-3">{{\App\Models\Attendance::sum('hour')}} h</h4>
                    </div>
                </div>
            </div>
          <div class="col-lg-4 col-md-12 col-6 mb-6">
            <div class="card h-100">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                  <div class="avatar flex-shrink-0">
                    <img src="{{ asset('assets/img/icons/unicons/wallet-info.png') }}" alt="wallet info" class="rounded" />
                  </div>
                </div>
                <p class="mb-1">Total Earning</p>
                <h4 class="card-title mb-3">{{\App\Models\Attendance::sum('earning')}}</h4>
              </div>
            </div>
          </div>
        </div>
    </div>

</div>
@endsection
