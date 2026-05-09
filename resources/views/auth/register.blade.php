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
                                        <i class="bi bi-eye-slash position-absolute text-muted" id="togglePassword" style="right: 15px; top: 12px; font-size: 1.2rem; cursor: pointer;" title="Show/Hide Password"></i>
                                    </div>
                                    <div class="password-requirements mt-2 small d-none" id="passwordRequirements">
                                        <i class="bi bi-x-circle me-1" id="passwordIcon"></i> <span id="passwordFeedback"></span>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label mb-0">Confirm Password</label>
                                    <div class="position-relative mt-2">
                                        <input type="password" class="form-control form-control-custom" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                                        <i class="bi bi-eye-slash position-absolute text-muted" id="togglePasswordConfirm" style="right: 15px; top: 12px; font-size: 1.2rem; cursor: pointer;" title="Show/Hide Password"></i>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const reqContainer = document.getElementById('passwordRequirements');
        const feedbackText = document.getElementById('passwordFeedback');
        const feedbackIcon = document.getElementById('passwordIcon');
        const passwordConfirmInput = document.getElementById('password_confirmation');

        passwordInput.addEventListener('input', function() {
            const val = this.value;
            
            // Cek kriteria
            const isLengthValid = val.length >= 8;
            const isUpperValid = /[A-Z]/.test(val);
            const isLowerValid = /[a-z]/.test(val);
            const isNumberValid = /[0-9]/.test(val);
            const isSpecialValid = /[^A-Za-z0-9]/.test(val);

            let missing = [];
            if (!isLengthValid) missing.push("8 karakter");
            if (!isUpperValid) missing.push("huruf besar");
            if (!isLowerValid) missing.push("huruf kecil");
            if (!isNumberValid) missing.push("angka");
            if (!isSpecialValid) missing.push("karakter unik");

            // Update UI feedback
            if (val.length === 0) {
                reqContainer.classList.add('d-none');
            } else {
                reqContainer.classList.remove('d-none');
                if (missing.length > 0) {
                    reqContainer.classList.remove('text-success');
                    reqContainer.classList.add('text-danger');
                    feedbackIcon.className = 'bi bi-x-circle me-1';
                    feedbackText.textContent = 'Kurang: ' + missing.join(', ');
                    passwordInput.setCustomValidity('Password kurang: ' + missing.join(', '));
                } else {
                    reqContainer.classList.remove('text-danger');
                    reqContainer.classList.add('text-success');
                    feedbackIcon.className = 'bi bi-check-circle-fill me-1';
                    feedbackText.textContent = 'Password sudah memenuhi kriteria.';
                    passwordInput.setCustomValidity('');
                }
            }

            // Validasi ulang konfirmasi password
            if (passwordConfirmInput.value) {
                if (passwordConfirmInput.value !== val) {
                    passwordConfirmInput.setCustomValidity('Konfirmasi password tidak cocok.');
                } else {
                    passwordConfirmInput.setCustomValidity('');
                }
            }
        });

        // Validasi konfirmasi password
        passwordConfirmInput.addEventListener('input', function() {
            if (this.value !== passwordInput.value) {
                this.setCustomValidity('Konfirmasi password tidak cocok.');
            } else {
                this.setCustomValidity('');
            }
        });

        // Toggle Password Visibility
        const togglePassword = document.getElementById('togglePassword');
        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });

        // Toggle Password Confirmation Visibility
        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
        togglePasswordConfirm.addEventListener('click', function () {
            const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmInput.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    });
</script>
@endsection
