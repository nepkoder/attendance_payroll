@extends('layouts/blankLayout')

@section('title', 'Employee Login')

@section('page-style')
  @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Login Card -->
        <div class="card px-sm-6 px-0">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-4">
              <a href="{{ url('/') }}" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">@include('_partials.macros')</span>
                <span class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
              </a>
            </div>
            <!-- /Logo -->

            <h4 class="mb-1">Welcome Employee! 👋</h4>
            <p class="mb-6">Please sign-in to your account</p>

            <form id="formAuthentication" class="mb-6" action="{{ route('employee.login.submit') }}" method="POST">
            @csrf

            <!-- Email or Username -->
              <div class="mb-3">
                <label for="login" class="form-label">Username</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror"
                       id="login" name="username" placeholder="Enter your username" value="{{ old('username') }}"
                       autofocus/>
                @error('username')
                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
              </div>

              <!-- Password -->
              <div class="mb-3 form-password-toggle">
                <label class="form-label" for="password">Password</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                         name="password" placeholder="••••••••••••" aria-describedby="password"/>
                  <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                  @error('password')
                  <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                  @enderror
                </div>
              </div>

              <!-- Remember Me -->
              <div class="mb-3 d-flex justify-content-between">
                <div class="form-check mb-0">
                  <input class="form-check-input" type="checkbox" id="remember-me"
                         name="remember" {{ old('remember') ? 'checked' : '' }} />
                  <label class="form-check-label" for="remember-me"> Remember Me </label>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
              </div>

              <!-- Register Link -->
              <p class="text-center">
                <span>Are you Admin User?</span>
                <a href="{{ url('admin/login') }}">
                  <span>Admin Login</span>
                </a>
              </p>
            </form>
          </div>
        </div>
        <!-- /Login Card -->
      </div>
    </div>
  </div>
@endsection
