@extends('layouts.auth')

@section('title', 'Sign Up for Monefy')

@section('content')
<div class="auth-page py-5">
    <div class="container">
        <div class="row justify-content-center">
            
            <div class="col-xl-9 col-lg-10">
                <div class="auth-card p-0">
                    <div class="row g-0 h-100 flex-lg-row-reverse">
                        
                        <!-- Right Image Side (Hidden on smaller screens) -->
                        <div class="col-lg-6 d-none d-lg-block p-4">
                            <div class="auth-side-image rounded-4">
                                <h3 class="fw-bold mb-3">Join Monefy Today</h3>
                                <p class="text-white-50 mb-0">Start managing your finances better and achieve your money goals effortlessly.</p>
                                <i class="bi bi-shield-check mt-5" style="font-size: 8rem; opacity: 0.8;"></i>
                            </div>
                        </div>

                        <!-- Left Form Side -->
                        <div class="col-lg-6 d-flex flex-column justify-content-center p-5">
                            <div class="text-center mb-4">
                                <a href="{{ route('home') }}" class="auth-brand">
                                    <img src="{{ asset('assets/images/logo.png') }}" alt="Monefy Logo" height="60" class="me-2">
                                    Monefy.
                                </a>
                                <h4 class="fw-bold text-dark">Create Account</h4>
                                <p class="text-muted">Start your financial journey</p>
                            </div>

                            <form class="api-form" action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control form-control-custom @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="John Doe" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <i class="bi bi-person position-absolute text-muted" style="right: 15px; top: 12px; font-size: 1.2rem;"></i>
                                    </div>
                                </div>

                                <div class="mb-3">
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

                                <div class="mb-3">
                                    <label for="password" class="form-label mb-0">Password</label>
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

                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label mb-0">Confirm Password</label>
                                    <div class="position-relative mt-2">
                                        <input type="password" class="form-control form-control-custom" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                                        <i class="bi bi-shield-lock position-absolute text-muted" style="right: 15px; top: 12px; font-size: 1.2rem;"></i>
                                    </div>
                                </div>

                                <div class="mb-4 form-check">
                                    <input type="checkbox" class="form-check-input" id="terms" required>
                                    <label class="form-check-label text-muted small" for="terms">I agree to the <a href="#" style="color: var(--primary-purple);">Terms of Service</a> and <a href="#" style="color: var(--primary-purple);">Privacy Policy</a></label>
                                </div>

                                <button type="submit" class="btn btn-primary-custom btn-auth mb-4">Sign Up</button>

                                <div class="text-center text-muted fw-medium">
                                    Already have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: var(--primary-purple);">Sign In</a>
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
