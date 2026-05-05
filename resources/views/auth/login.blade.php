@extends('layouts.auth')

@section('title', 'Login to Monefy')

@section('content')
<div class="auth-page py-5">
    <div class="container">
        <div class="row justify-content-center">
            
            <div class="col-xl-9 col-lg-10">
                <div class="auth-card p-0">
                    <div class="row g-0 h-100">
                        
                        <!-- Left Image Side (Hidden on smaller screens) -->
                        <div class="col-lg-6 d-none d-lg-block p-4">
                            <div class="auth-side-image rounded-4">
                                <h3 class="fw-bold mb-3">Welcome to Monefy</h3>
                                <p class="text-white-50 mb-0">Record, manage, and track your transactions easily in one place. Your perfect financial companion.</p>
                                <!-- Optionally an illustration could go here -->
                                <i class="bi bi-wallet2 mt-5" style="font-size: 8rem; opacity: 0.8;"></i>
                            </div>
                        </div>

                        <!-- Right Form Side -->
                        <div class="col-lg-6 d-flex flex-column justify-content-center p-5">
                            <div class="text-center mb-4">
                                <a href="{{ route('home') }}" class="auth-brand">
                                    <img src="{{ asset('assets/images/logo.png') }}" alt="Monefy Logo" height="60" class="me-2">
                                    Monefy.
                                </a>
                                <h4 class="fw-bold text-dark">Sign In</h4>
                                <p class="text-muted">Stay updated on your finances</p>
                            </div>

                            <form class="api-form" action="{{ route('login') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="email" class="form-label">Email Address</label>
                                    <div class="position-relative">
                                        <input type="email" class="form-control form-control-custom @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="mochi@example.com" required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <i class="bi bi-envelope position-absolute text-muted" style="right: 15px; top: 12px; font-size: 1.2rem;"></i>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label for="password" class="form-label mb-0">Password</label>
                                        <a href="#" class="text-decoration-none small text-primary fw-semibold" style="color: var(--primary-purple) !important;">Forgot password?</a>
                                    </div>
                                    <div class="position-relative mt-2">
                                        <input type="password" class="form-control form-control-custom @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <i class="bi bi-lock position-absolute text-muted" style="right: 15px; top: 12px; font-size: 1.2rem;"></i>
                                    </div>
                                </div>

                                <div class="mb-5 form-check">
                                    <input type="checkbox" class="form-check-input" name="remember" id="rememberMe" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted" for="rememberMe">Remember me</label>
                                </div>

                                <button type="submit" class="btn btn-primary-custom btn-auth mb-4">Sign In</button>

                                <div class="text-center text-muted fw-medium">
                                    Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: var(--primary-purple);">Sign Up</a>
                                </div>
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
