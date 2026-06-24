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
                    <div class="profile-avatar-container mb-3 position-relative d-inline-block">
                        <div class="profile-avatar overflow-hidden" style="width: 120px; height: 120px; border-radius: 50%; background-color: var(--primary-purple); color: white; display: flex; align-items: center; justify-content: center; font-size: 3rem; margin: 0 auto;">
                            @php
                                // Use session as fallback after upload, add cache-busting to prevent browser caching
                                $avatarUrl = $user['avatar'] ?? session('user.avatar');
                                if ($avatarUrl) {
                                    if (str_contains($avatarUrl, '/storage/v1/s3/')) {
                                        $avatarUrl = str_replace('/storage/v1/s3/', '/storage/v1/object/public/', $avatarUrl);
                                    }
                                    $avatarUrl = $avatarUrl . (str_contains($avatarUrl, '?') ? '&' : '?') . 'v=' . time();
                                }
                            @endphp
                            @if(!empty($avatarUrl))
                                <img src="{{ $avatarUrl }}" alt="Avatar" id="userAvatarImg" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i class="bi bi-person-fill" id="userAvatarIcon"></i>
                                <img src="" alt="Avatar" id="userAvatarImg" class="d-none" style="width: 100%; height: 100%; object-fit: cover;">
                            @endif
                        </div>
                        <label for="profileUpload" class="edit-badge position-absolute" style="cursor: pointer; bottom: 0; right: 0; background: var(--income-green); color: white; border-radius: 50%; padding: 8px; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border: 3px solid white;" title="Change Profile Picture">
                            <i class="bi bi-pencil-fill" style="font-size: 0.9rem;"></i>
                        </label>
                        <input type="file" id="profileUpload" class="d-none" accept="image/*" onchange="uploadProfilePicture(this)">
                    </div>
                    <h3 class="fw-bold mb-1" style="color: var(--primary-purple);">{{ $user['name'] ?? 'Guest' }}</h3>
                    <p class="mb-0" style="color: #A0AEC0;">{{ $user['email'] ?? 'user@example.com' }}</p>
                </div>
            </div>

            <!-- Right Side: Settings Area -->
            <div class="col-lg-8">
                <h4 class="fw-bold mb-4" style="color: var(--text-dark);">Account Overviews</h4>
                
                <div class="row">
                    <div class="col-md-12">
                        <!-- Card 1 -->
                        <a href="{{ route('wallet.index') }}" class="setting-card">
                            <div class="setting-card-icon"><i class="bi bi-wallet2"></i></div>
                            <span>Manage Your Wallets</span>
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
                        <form action="{{ Route::has('logout') ? route('logout') : '#' }}" method="POST" class="m-0 p-0">
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
                },
                body: formData
            });
            
            if (response.ok) {
                const result = await response.json();
                // Update the avatar UI
                const imgTag = document.getElementById('userAvatarImg');
                const iconTag = document.getElementById('userAvatarIcon');
                
                // Add cache-busting parameter with timestamp to force image reload
                const avatarUrlWithCache = result.avatar_url + (result.avatar_url.includes('?') ? '&' : '?') + 'v=' + Date.now();
                imgTag.src = avatarUrlWithCache;
                imgTag.classList.remove('d-none');
                
                if(iconTag) {
                    iconTag.classList.add('d-none');
                }
                
                alert('Success! Profile picture updated.');
                
                // Optional: Refresh page after 2 seconds to ensure Blade view gets fresh data from API
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                const err = await response.json().catch(()=>({}));
                alert('Error: ' + (err.message || 'Failed to upload.'));
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

