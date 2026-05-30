@extends('layouts.app')

@section('title', 'Monefy - Wishlist')

@push('styles')
<style>
    :root {
        --primary-purple: #7C4CFF;
        --soft-purple: #F3F0FF;
        --text-dark: #1E293B;
        --success-green: #2ED573;
    }

    body { background-color: #ffffff !important; }

    .wishlist-hero {
        background: linear-gradient(135deg, #7C4CFF 0%, #C084FC 100%);
        border-radius: 30px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 15px 35px rgba(124, 76, 255, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .wishlist-stats {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-around;
        text-align: center;
    }

    .stat-item h5 {
        font-weight: 800;
        color: var(--primary-purple);
        margin-bottom: 0.2rem;
    }

    .stat-item span {
        font-size: 0.9rem;
        color: #64748B;
        font-weight: 500;
    }

    .nav-tabs {
        border-bottom: 2px solid #F1F5F9;
        margin-bottom: 1.5rem;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #64748B;
        font-weight: 600;
        padding: 10px 20px;
        border-bottom: 3px solid transparent;
        transition: 0.3s;
    }

    .nav-tabs .nav-link.active {
        color: var(--primary-purple);
        background: transparent;
        border-bottom: 3px solid var(--primary-purple);
    }

    .wishlist-item {
        background: white;
        border: 1px solid #F1F5F9;
        border-radius: 20px;
        padding: 1.2rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s;
    }
    .wishlist-item:hover {
        transform: translateX(10px);
        border-color: var(--primary-purple);
        box-shadow: 0 10px 20px rgba(0,0,0,0.03);
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: capitalize;
    }
    .status-terbeli { background: #E8F9F1; color: var(--success-green); }
    .status-belum_terbeli { background: #F1F5F9; color: #94A3B8; }

    .wishlist-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
    }

    .wishlist-price {
        font-size: 0.9rem;
        color: var(--primary-purple);
        font-weight: 700;
    }

    .wishlist-note {
        font-size: 0.8rem;
        color: #64748B;
        margin-top: 4px;
    }

    .btn-status-toggle {
        background: transparent;
        border: 2px solid #E2E8F0;
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        color: transparent;
    }
    .wishlist-item.is-purchased .btn-status-toggle {
        background: var(--success-green);
        border-color: var(--success-green);
        color: white;
    }
    .wishlist-item.is-purchased .wishlist-name {
        text-decoration: line-through;
        opacity: 0.6;
    }

    .btn-add-wishlist {
        background: var(--primary-purple);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 15px;
        font-weight: 700;
        transition: 0.3s;
    }
    .btn-add-wishlist:hover { transform: scale(1.05); box-shadow: 0 10px 20px rgba(124, 76, 255, 0.3); }

    .btn-pay {
        background: #F3F0FF;
        color: var(--primary-purple);
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.85rem;
        transition: 0.3s;
    }
    .btn-pay:hover {
        background: var(--primary-purple);
        color: white;
    }

    .premium-modal-content { border-radius: 30px; border: none; overflow: hidden; }

    /* Celebration Animation */
    @keyframes celebrate {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    .wishlist-item.is-purchased {
        animation: celebrate 0.5s ease;
        background: #F0FFF4;
        border-color: var(--success-green);
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--primary-purple); font-size: 1.3rem;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h3 class="fw-bold mb-0">Wishlist Impian</h3>
    </div>

    <div class="wishlist-hero">
        <div>
            <h4 class="fw-bold mb-1">Daftar Keinginan</h4>
            <p class="mb-0 opacity-75">Tulis dan wujudkan impianmu!</p>
        </div>
        <button class="btn-add-wishlist" data-bs-toggle="modal" data-bs-target="#addWishlistModal">
            <i class="bi bi-plus-lg me-2"></i> Tambah Baru
        </button>
    </div>

    @php
        $activeWishlists = collect($wishlists)->filter(fn($w) => $w['status'] !== 'terbeli');
        $achievedWishlists = collect($wishlists)->filter(fn($w) => $w['status'] === 'terbeli');
        $totalNilai = $activeWishlists->sum('target_amount');
        $totalWishlists = count($wishlists);
        $totalAchieved = $achievedWishlists->count();
    @endphp

    <div class="wishlist-stats">
        <div class="stat-item">
            <h5>Rp {{ number_format($totalNilai, 0, ',', '.') }}</h5>
            <span>Total Nilai Wishlist Aktif</span>
        </div>
        <div class="stat-item">
            <h5 class="text-success">{{ $totalAchieved }} / {{ $totalWishlists }}</h5>
            <span>Wishlist Tercapai</span>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="wishlistTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="aktif-tab" data-bs-toggle="tab" data-bs-target="#aktif" type="button" role="tab">Aktif ({{ $activeWishlists->count() }})</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tercapai-tab" data-bs-toggle="tab" data-bs-target="#tercapai" type="button" role="tab">Tercapai ({{ $achievedWishlists->count() }})</button>
        </li>
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content" id="wishlistTabsContent">
        <!-- Aktif Tab -->
        <div class="tab-pane fade show active" id="aktif" role="tabpanel">
            <div class="wishlist-container">
                @forelse($activeWishlists as $item)
                <div class="wishlist-item">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box" style="width: 45px; height: 45px; background: #F3F0FF; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--primary-purple);">
                            <i class="bi bi-star-fill fs-5"></i>
                        </div>
                        <div>
                            <div class="wishlist-name">{{ $item['name'] }}</div>
                            <div class="wishlist-price">Rp {{ number_format($item['target_amount'] ?? 0, 0, ',', '.') }}</div>
                            @if(!empty($item['notes']))
                            <div class="wishlist-note"><i class="bi bi-info-circle"></i> {{ $item['notes'] }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn-pay" onclick="openPayModal({{ $item['id'] }}, '{{ $item['name'] }}', {{ $item['target_amount'] ?? 0 }})">
                            <i class="bi bi-wallet2"></i> Beli
                        </button>
                        <button type="button" class="btn btn-light rounded-pill p-2 px-3" 
                                onclick="confirmDelete({{ $item['id'] }}, '{{ $item['name'] }}')">
                            <i class="bi bi-trash text-danger"></i>
                        </button>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/3504/3504384.png" width="100" class="opacity-25 mb-3">
                    <h5 class="text-muted fw-bold">Belum ada wishlist aktif</h5>
                    <p class="text-muted small">Mulai tulis impianmu hari ini!</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Tercapai Tab -->
        <div class="tab-pane fade" id="tercapai" role="tabpanel">
            <div class="wishlist-container">
                @forelse($achievedWishlists as $item)
                <div class="wishlist-item is-purchased">
                    <div class="d-flex align-items-center gap-3">
                        <div class="btn-status-toggle">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div>
                            <div class="wishlist-name">{{ $item['name'] }}</div>
                            <span class="status-badge status-terbeli">Tercapai</span>
                            <div class="wishlist-price mt-1 opacity-75">Rp {{ number_format($item['target_amount'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light rounded-pill p-2 px-3" 
                                onclick="confirmDelete({{ $item['id'] }}, '{{ $item['name'] }}')">
                            <i class="bi bi-trash text-danger"></i>
                        </button>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <h5 class="text-muted fw-bold">Belum ada wishlist yang tercapai</h5>
                    <p class="text-muted small">Tetap semangat menabung!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Pay Wishlist Modal --}}
<div class="modal fade" id="payWishlistModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content premium-modal-content">
            <div class="modal-header p-4 pb-2 border-0">
                <h5 class="fw-bold mb-0">Beli Wishlist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info rounded-4 border-0 mb-4" style="background: var(--soft-purple); color: var(--primary-purple);">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-cart-check-fill fs-3"></i>
                        <div>
                            <div class="small fw-bold opacity-75">Membeli:</div>
                            <div class="fw-bold fs-5" id="payWishlistName"></div>
                            <div class="fw-bold" id="payWishlistAmount"></div>
                        </div>
                    </div>
                </div>

                <form id="payWishlistForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Pilih Dompet / Sumber Dana</label>
                        <select name="wallet_id" class="form-select form-select-lg border-0 bg-light rounded-4" required>
                            <option value="">Pilih Dompet...</option>
                            @if(isset($wallets))
                                @foreach($wallets as $wallet)
                                    <option value="{{ $wallet['id'] }}">
                                        {{ $wallet['name_wallet'] }} - Rp {{ number_format($wallet['balance'], 0, ',', '.') }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <button type="submit" class="btn w-100 py-3 fw-bold rounded-4 shadow-sm text-white" style="background: var(--success-green); border:none;">
                        Bayar Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteWishlistModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content premium-modal-content text-center p-4">
            <div class="mb-3">
                <div class="d-inline-flex align-items-center justify-content-center bg-light-danger rounded-circle" style="width: 80px; height: 80px; background: #FFF0F1;">
                    <i class="bi bi-exclamation-circle text-danger" style="font-size: 2.5rem;"></i>
                </div>
            </div>
            <h5 class="fw-bold text-dark">Hapus Wishlist?</h5>
            <p class="text-muted small mb-4">Apakah kamu yakin ingin menghapus "<span id="deleteWishlistName" class="fw-bold"></span>"?</p>
            
            <form id="deleteWishlistForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light flex-fill py-2 fw-bold rounded-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger flex-fill py-2 fw-bold rounded-4 shadow-sm">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Add Wishlist Modal --}}
<div class="modal fade" id="addWishlistModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content premium-modal-content">
            <div class="modal-header p-4 pb-2 border-0">
                <h5 class="fw-bold mb-0">Tambah Wishlist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('wishlist.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Barang / Impian</label>
                        <input type="text" name="name" class="form-control form-control-lg border-0 bg-light rounded-4" placeholder="Misal: iPhone 15, Liburan ke Bali..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Target Dana (Rp)</label>
                        <input type="number" name="target_amount" class="form-control form-control-lg border-0 bg-light rounded-4" placeholder="Misal: 15000000" min="0" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" class="form-control border-0 bg-light rounded-4" rows="2" placeholder="Warna hitam, beli di iBox..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-4 shadow-sm" style="background: var(--primary-purple); border:none;">
                        Simpan Wishlist
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id, name) {
    document.getElementById('deleteWishlistName').innerText = name;
    document.getElementById('deleteWishlistForm').action = `/wishlist/${id}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteWishlistModal'));
    modal.show();
}

function openPayModal(id, name, amount) {
    document.getElementById('payWishlistName').innerText = name;
    document.getElementById('payWishlistAmount').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    document.getElementById('payWishlistForm').action = `/wishlist/${id}/pay`;
    const modal = new bootstrap.Modal(document.getElementById('payWishlistModal'));
    modal.show();
}

// Efek Selebrasi saat berhasil bayar
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success') && str_contains(session('success'), 'berhasil dibayar'))
        triggerConfetti();
    @endif
});

function triggerConfetti() {
    const colors = ['#7C4CFF', '#2ED573', '#FF4757', '#FFA502'];
    for(let i = 0; i < 50; i++) {
        const confetti = document.createElement('div');
        confetti.style.position = 'fixed';
        confetti.style.width = '10px';
        confetti.style.height = '10px';
        confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        confetti.style.left = Math.random() * 100 + 'vw';
        confetti.style.top = '-10px';
        confetti.style.zIndex = '9999';
        confetti.style.borderRadius = '2px';
        confetti.style.transform = `rotate(${Math.random() * 360}deg)`;
        
        document.body.appendChild(confetti);

        const animation = confetti.animate([
            { top: '-10px', opacity: 1, transform: `rotate(0deg) translateX(0)` },
            { top: '100vh', opacity: 0, transform: `rotate(${Math.random() * 1000}deg) translateX(${Math.random() * 200 - 100}px)` }
        ], {
            duration: Math.random() * 2000 + 1000,
            easing: 'cubic-bezier(0, .9, .57, 1)'
        });

        animation.onfinish = () => confetti.remove();
    }
}
</script>
@endpush

@endsection
