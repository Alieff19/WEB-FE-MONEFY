@extends('layouts.app')

@section('title', 'Monefy - Profile')

@section('content')
<!-- Main Profile Container -->
    <div class="container my-5">
        <div class="row g-5">
            <!-- Left Side: Profile Info Card -->
            <div class="col-lg-4">
                <div class="bg-white p-5 rounded-4 text-center" style="box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
                    <!-- Circular Avatar Profile -->
                    <div class="profile-avatar-container mb-3">
                        <div class="profile-avatar">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <label for="profileUpload" class="edit-badge" style="cursor: pointer;" title="Change Profile Picture">
                            <i class="bi bi-pencil-fill"></i>
                        </label>
                        <input type="file" id="profileUpload" class="d-none" accept="image/*" onchange="uploadProfilePicture(this)">
                    </div>
                    <h3 class="fw-bold mb-1" style="color: var(--primary-purple);">{{ $user->name ?? 'Guest' }}</h3>
                    <p class="mb-0" style="color: #A0AEC0;">{{ $user->email ?? 'user@example.com' }}</p>
                </div>
            </div>

            <!-- Right Side: Settings Area -->
            <div class="col-lg-8">
                <h4 class="fw-bold mb-4" style="color: var(--text-dark);">Account Overviews</h4>
                
                <div class="row">
                    <div class="col-md-12">
                        <!-- Card 1 -->
                        <a href="#" data-bs-toggle="modal" data-bs-target="#addWalletModal" class="setting-card">
                            <div class="setting-card-icon"><i class="bi bi-wallet2"></i></div>
                            <span>Add your wallet</span>
                            <i class="bi bi-chevron-right ms-auto text-muted"></i>
                        </a>
                    </div>
                    
                    <div class="col-md-12">
                        <!-- Card 2 -->
                        <a href="{{ route('help') }}" class="setting-card">
                            <div class="setting-card-icon"><i class="bi bi-question-circle-fill"></i></div>
                            <span>Help Center</span>
                            <i class="bi bi-chevron-right ms-auto text-muted"></i>
                        </a>
                    </div>
                    
                    <div class="col-md-12 mt-4">
                        <!-- Card 3 -->
                        <form action="{{ Route::has('logout') ? route('logout') : '#' }}" method="POST" class="api-form m-0 p-0">
                            @csrf
                            <button type="submit" class="setting-card logout-card w-100 text-start border-0 bg-transparent" style="cursor:pointer; display:flex; align-items:center;">
                                <div class="setting-card-icon"><i class="bi bi-box-arrow-right"></i></div>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    async function uploadProfilePicture(input) {
        if (!input.files || input.files.length === 0) return;
        
        const file = input.files[0];
        const formData = new FormData();
        formData.append('avatar', file);
        
        // Visual loading state
        const label = document.querySelector('.edit-badge');
        const originalIcon = label.innerHTML;
        label.innerHTML = '<span class="spinner-border spinner-border-sm text-white" role="status"></span>';
        
        try {
            const url = '{{ Route::has("profile.upload") ? route("profile.upload") : "#" }}';
            if (url === '#') {
                alert('API for profile upload is not ready from backend.');
                return;
            }
            
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    // Note: Do NOT set Content-Type manually when sending FormData, the browser sets it with the correct boundary!
                },
                body: formData
            });
            
            if (response.ok) {
                alert('Success! Profile picture sent to API.');
                // Here backend would return the new image URL, and we would update the avatar UI.
            } else {
                const err = await response.json().catch(()=>({}));
                alert('Backend Error: ' + (err.message || 'Failed to upload.'));
            }
        } catch (e) {
            console.error(e);
            alert('Network error. Failed to reach API.');
        } finally {
            label.innerHTML = originalIcon;
            input.value = ""; // reset input
        }
    }
</script>
@endpush

