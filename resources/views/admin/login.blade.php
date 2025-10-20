@extends('layouts/blankLayout')

@section('title', 'Login Admin')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Register -->
            <div class="card px-sm-6 px-0">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros')</span>
                            <span class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-1">Welcome to {{ config('variables.templateName') }}! 👋</h4>
                    <p class="mb-6">Please sign-in to your account and start the adventure</p>

                  <form id="formAuthentication" class="mb-6" action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                      <label for="username" class="form-label">Username</label>
                      <input
                        type="text"
                        class="form-control"
                        id="username"
                        name="username"
                        placeholder="Enter your username"
                        required
                        autofocus
                      />
                    </div>

                    <div class="mb-6 form-password-toggle">
                      <label class="form-label" for="password">Password</label>
                      <div class="input-group input-group-merge">
                        <input
                          type="password"
                          id="password"
                          class="form-control"
                          name="password"
                          placeholder="••••••••••••"
                          required
                        />
                        <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                      </div>
                    </div>

                    <div class="mb-8 d-flex justify-content-between">
                      <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />
                        <label class="form-check-label" for="remember-me"> Remember Me </label>
                      </div>
                    </div>

                    <div class="mb-6">
                      <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                    </div>
                  </form>


                  <p class="text-center">
                        <span>Are you Employee?</span>
                        <a href="{{ url('employee/login') }}">
                            <span>Employee Login</span>
                        </a>
                    </p>
                </div>
            </div>
            <!-- /Register -->
        </div>
    </div>
</div>
@endsection
