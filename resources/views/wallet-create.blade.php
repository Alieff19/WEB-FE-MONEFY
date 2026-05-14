@extends('layouts.app')

@section('title', 'Monefy - Create Premium Wallet')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%);
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.3);
        --text-slate: #475569;
        --text-dark: #1E293B;
    }

    body { 
        background-color: #F0F4F8 !important; 
        font-family: 'Outfit', sans-serif !important;
        overflow-x: hidden;
    }

    /* Background Decorations */
    .bg-blob {
        position: fixed;
        width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(124, 58, 237, 0.1) 0%, rgba(79, 70, 229, 0) 70%);
        border-radius: 50%;
        z-index: -1;
        filter: blur(60px);
    }
    .blob-1 { top: -200px; right: -100px; }
    .blob-2 { bottom: -200px; left: -100px; }

    .premium-container {
        max-width: 1200px;
        margin: 3rem auto;
        padding: 0 1.5rem;
        perspective: 1000px;
    }

    .main-layout {
        display: flex;
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 40px;
        overflow: hidden;
        box-shadow: 0 40px 100px rgba(0, 0, 0, 0.08);
        min-height: 700px;
    }

    /* Left Side: Visual Experience */
    .visual-experience {
        flex: 1;
        background: #1E1B4B;
        position: relative;
        padding: 4rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        overflow: hidden;
    }
    
    .visual-experience::before {
        content: '';
        position: absolute;
        width: 150%; height: 150%;
        background: radial-gradient(circle at 30% 30%, rgba(124, 58, 237, 0.3) 0%, transparent 50%);
        top: -25%; left: -25%;
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

    .card-preview-3d {
        width: 100%;
        max-width: 420px;
        height: 260px;
        background: var(--primary-gradient);
        border-radius: 32px;
        padding: 2.5rem;
        position: relative;
        z-index: 10;
        box-shadow: 0 30px 60px rgba(0,0,0,0.4), inset 0 0 0 1px rgba(255,255,255,0.2);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        transform-style: preserve-3d;
    }
    .card-preview-3d:hover { transform: translateY(-15px) rotateX(5deg) rotateY(-5deg); }

    .card-glass-shine {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, transparent 50%, rgba(255,255,255,0.05) 100%);
        border-radius: 32px;
        pointer-events: none;
    }

    .preview-chip { width: 55px; height: 42px; background: linear-gradient(135deg, #fceabb 0%, #f8b500 100%); border-radius: 10px; margin-bottom: 1.5rem; position: relative; overflow: hidden; }
    .preview-chip::after { content: ''; position: absolute; top:0; left:0; width:100%; height:100%; background: repeating-linear-gradient(90deg, transparent, transparent 5px, rgba(0,0,0,0.1) 5px, rgba(0,0,0,0.1) 6px); }

    .preview-label { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 3px; opacity: 0.7; margin-bottom: 0.5rem; display: block; }
    .preview-name { font-size: 1.6rem; font-weight: 800; margin-bottom: 1rem; height: 2rem; letter-spacing: 1px; text-shadow: 0 2px 10px rgba(0,0,0,0.2); }
    .preview-balance-group { margin-top: auto; }
    .preview-balance { font-size: 2.2rem; font-weight: 600; font-family: 'Outfit', sans-serif; }
    .preview-icon-bg { position: absolute; bottom: 2rem; right: 2rem; font-size: 5rem; opacity: 0.15; transition: 0.3s; }

    /* Right Side: Form Content */
    .form-content {
        flex: 1.2;
        padding: 5rem 4.5rem;
        background: white;
        position: relative;
    }

    .form-header { margin-bottom: 4rem; }
    .form-title { font-size: 2.5rem; font-weight: 800; color: var(--text-dark); margin-bottom: 0.8rem; letter-spacing: -1px; }
    .form-subtitle { color: var(--text-slate); font-size: 1.1rem; }

    .premium-field { margin-bottom: 2.5rem; }
    .premium-label { font-size: 0.95rem; font-weight: 700; color: #64748B; margin-bottom: 1rem; display: block; }

    .premium-input-wrapper { position: relative; display: flex; align-items: center; }
    .premium-input {
        width: 100%;
        padding: 1.2rem 1.6rem;
        background: #F8FAFC;
        border: 2px solid #E2E8F0;
        border-radius: 20px;
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--text-dark);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .premium-input:focus {
        background: white;
        border-color: #6366F1;
        outline: none;
        box-shadow: 0 20px 40px rgba(99, 102, 241, 0.1);
        transform: translateY(-2px);
    }
    .premium-input::placeholder { color: #94A3B8; font-weight: 400; }
    
    .premium-select { cursor: pointer; appearance: none; padding-right: 3rem; }
    .select-arrow { position: absolute; right: 24px; color: #94A3B8; font-size: 1.2rem; pointer-events: none; }

    .locked-badge {
        position: absolute; right: 15px; top: -35px;
        background: #EEF2FF; color: #4F46E5;
        padding: 4px 12px; border-radius: 10px;
        font-size: 0.75rem; font-weight: 700;
        border: 1px solid #E0E7FF;
    }

    .theme-grid { display: flex; gap: 15px; margin-top: 15px; }
    .theme-opt {
        width: 50px; height: 50px; border-radius: 18px; cursor: pointer;
        border: 4px solid transparent; transition: all 0.3s;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    .theme-opt.active { border-color: #6366F1; transform: scale(1.15) rotate(5deg); box-shadow: 0 15px 30px rgba(99, 102, 241, 0.3); }

    .btn-create-premium {
        width: 100%;
        background: #4F46E5;
        background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
        color: white;
        border: none;
        padding: 1.4rem;
        border-radius: 24px;
        font-size: 1.25rem;
        font-weight: 800;
        margin-top: 2rem;
        cursor: pointer;
        transition: all 0.4s;
        box-shadow: 0 20px 50px rgba(79, 70, 229, 0.3);
        display: flex; align-items: center; justify-content: center; gap: 12px;
    }
    .btn-create-premium:hover {
        transform: translateY(-6px);
        box-shadow: 0 25px 60px rgba(79, 70, 229, 0.45);
        filter: brightness(1.1);
    }

    .btn-back-link {
        display: inline-flex; align-items: center; gap: 10px;
        color: #94A3B8; text-decoration: none; font-weight: 600;
        margin-bottom: 2rem; transition: 0.3s;
    }
    .btn-back-link:hover { color: #4F46E5; transform: translateX(-5px); }

    /* Responsive */
    @media (max-width: 1000px) {
        .main-layout { flex-direction: column; }
        .visual-experience { padding: 5rem 2rem; }
        .form-content { padding: 4rem 2rem; }
    }
</style>
@endpush

@section('content')
<div class="bg-blob blob-1"></div>
<div class="bg-blob blob-2"></div>

<div class="premium-container">
    <a href="{{ route('wallet.index') }}" class="btn-back-link">
        <i class="bi bi-arrow-left-circle-fill fs-4"></i> Return to Wallets
    </a>

    <div class="main-layout">
        {{-- Left: Immersive Preview --}}
        <div class="visual-experience">
            <div class="card-preview-3d" id="cardPreview">
                <div class="card-glass-shine"></div>
                
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="preview-label" id="labelType">Cash Wallet</span>
                        <div class="preview-chip"></div>
                    </div>
                    <div class="d-flex">
                        <div style="width:35px; height:35px; border-radius:50%; background:rgba(255,255,255,0.2); backdrop-filter:blur(5px);"></div>
                        <div style="width:35px; height:35px; border-radius:50%; background:rgba(255,255,255,0.15); margin-left:-15px;"></div>
                    </div>
                </div>

                <div>
                    <div class="preview-name" id="viewName">MY WALLET</div>
                    <div class="preview-balance-group">
                        <span class="preview-label" style="margin-bottom:0">Starting Balance</span>
                        <div class="preview-balance" id="viewBalance">Rp 0</div>
                    </div>
                </div>

                <i class="bi bi-cash-stack preview-icon-bg" id="viewIcon"></i>
            </div>

            <div class="mt-5 text-center px-4" style="z-index: 10;">
                <h3 class="fw-bold">Visual Preview</h3>
                <p class="opacity-75">See how your new wallet looks in real-time as you fill the form.</p>
            </div>
        </div>

        {{-- Right: Premium Form --}}
        <div class="form-content">
            <div class="form-header">
                <h1 class="form-title">Create Wallet</h1>
                <p class="form-subtitle">Define your new financial asset with precision</p>
            </div>

            <form action="{{ route('wallet.store.page') }}" method="POST" id="premiumWalletForm">
                @csrf
                
                {{-- Account Category --}}
                <div class="premium-field">
                    <label class="premium-label">Select Account Category</label>
                    <div class="premium-input-wrapper">
                        @if(isset($defaultType))
                            <div class="locked-badge"><i class="bi bi-lock-fill me-1"></i> Auto-selected</div>
                        @endif
                        <select name="category" id="inputCategory" class="premium-input premium-select" {{ isset($defaultType) ? 'disabled' : '' }}>
                            <option value="cash" {{ (old('category', $defaultType ?? 'cash') == 'cash') ? 'selected' : '' }}>Cash / Physical Money</option>
                            <option value="bank" {{ (old('category', $defaultType ?? 'cash') == 'bank') ? 'selected' : '' }}>Bank Account / Savings</option>
                            <option value="e-wallet" {{ (old('category', $defaultType ?? 'cash') == 'e-wallet') ? 'selected' : '' }}>Digital Wallet / E-Wallet</option>
                        </select>
                        <i class="bi bi-chevron-down select-arrow"></i>
                        @if(isset($defaultType))
                            <input type="hidden" name="category" value="{{ $defaultType }}">
                        @endif
                    </div>
                </div>

                {{-- Wallet Name --}}
                <div class="premium-field">
                    <label class="premium-label">What should we call this?</label>
                    <div class="premium-input-wrapper">
                        <input type="text" name="name_wallet" id="inputName" class="premium-input" 
                               placeholder="e.g. Monthly Savings" required maxlength="30"
                               value="{{ old('name_wallet') }}">
                    </div>
                </div>

                {{-- Starting Balance --}}
                <div class="premium-field">
                    <label class="premium-label">Starting Balance</label>
                    <div class="premium-input-wrapper">
                        <span style="position:absolute; left:20px; font-weight:700; color:#4F46E5; font-size:1.2rem;">Rp</span>
                        <input type="text" inputmode="numeric" name="balance" id="inputBalance" class="premium-input" 
                               placeholder="0" required style="padding-left: 3.5rem; color:#4F46E5; font-size: 1.5rem;"
                               value="{{ old('balance', '0') }}">
                    </div>
                    <small class="text-muted mt-2 d-block">Current available balance in this account.</small>
                </div>

                {{-- Theme Selection --}}
                <div class="premium-field">
                    <label class="premium-label">Choose Visual Theme</label>
                    <div class="theme-grid">
                        <div class="theme-opt active" style="background: linear-gradient(135deg, #7C3AED, #4F46E5)" data-grad="linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%)"></div>
                        <div class="theme-opt" style="background: linear-gradient(135deg, #F43F5E, #E11D48)" data-grad="linear-gradient(135deg, #F43F5E 0%, #E11D48 100%)"></div>
                        <div class="theme-opt" style="background: linear-gradient(135deg, #10B981, #059669)" data-grad="linear-gradient(135deg, #10B981 0%, #059669 100%)"></div>
                        <div class="theme-opt" style="background: linear-gradient(135deg, #F59E0B, #D97706)" data-grad="linear-gradient(135deg, #F59E0B 0%, #D97706 100%)"></div>
                        <div class="theme-opt" style="background: linear-gradient(135deg, #0F172A, #1E293B)" data-grad="linear-gradient(135deg, #0F172A 0%, #1E293B 100%)"></div>
                    </div>
                </div>

                <button type="submit" class="btn-create-premium">
                    <i class="bi bi-plus-circle-fill"></i> Initialize Wallet
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const inputName = document.getElementById('inputName');
    const inputBalance = document.getElementById('inputBalance');
    const inputCategory = document.getElementById('inputCategory');
    
    const viewName = document.getElementById('viewName');
    const viewBalance = document.getElementById('viewBalance');
    const labelType = document.getElementById('labelType');
    const viewIcon = document.getElementById('viewIcon');
    const cardPreview = document.getElementById('cardPreview');

    const configMap = {
        'cash':     { label: 'Cash Wallet',    icon: 'bi-cash-stack',    default: 'My Cash' },
        'bank':     { label: 'Bank Account',   icon: 'bi-bank2',         default: 'Savings Account' },
        'e-wallet': { label: 'Digital Wallet', icon: 'bi-phone-vibrate', default: 'E-Wallet' }
    };

    function updatePreview() {
        const cat = inputCategory.value;
        const cfg = configMap[cat];
        labelType.innerText = cfg.label;
        viewIcon.className = `bi ${cfg.icon} preview-icon-bg`;
        
        if (!inputName.value || Object.values(configMap).some(c => c.default === inputName.value)) {
            inputName.value = cfg.default;
            viewName.innerText = cfg.default.toUpperCase();
        }
    }

    inputName.addEventListener('input', (e) => {
        viewName.innerText = (e.target.value || 'MY WALLET').toUpperCase();
    });

    inputBalance.addEventListener('input', (e) => {
        let raw = e.target.value.replace(/[^0-9]/g, '');
        if (raw) {
            let num = parseInt(raw);
            let formatted = num.toLocaleString('id-ID');
            e.target.value = formatted;
            viewBalance.innerText = 'Rp ' + formatted;
            // Subtle pulse effect on card
            cardPreview.style.transform = 'scale(1.02) translateY(-15px) rotateX(5deg)';
            setTimeout(() => cardPreview.style.transform = 'translateY(-15px) rotateX(5deg)', 150);
        } else {
            e.target.value = '';
            viewBalance.innerText = 'Rp 0';
        }
    });

    inputCategory.addEventListener('change', updatePreview);
    updatePreview(); // Init

    document.querySelectorAll('.theme-opt').forEach(dot => {
        dot.addEventListener('click', function() {
            document.querySelectorAll('.theme-opt').forEach(d => d.classList.remove('active'));
            this.classList.add('active');
            cardPreview.style.background = this.dataset.grad;
        });
    });

    document.getElementById('premiumWalletForm').addEventListener('submit', function() {
        // Strip dots before sending to backend
        inputBalance.value = inputBalance.value.replace(/\./g, '');
    });

    // 3D Tilt Effect
    cardPreview.addEventListener('mousemove', (e) => {
        const { left, top, width, height } = cardPreview.getBoundingClientRect();
        const x = (e.clientX - left) / width - 0.5;
        const y = (e.clientY - top) / height - 0.5;
        cardPreview.style.transform = `translateY(-15px) rotateX(${y * -20}deg) rotateY(${x * 20}deg)`;
    });

    cardPreview.addEventListener('mouseleave', () => {
        cardPreview.style.transform = `translateY(0) rotateX(0) rotateY(0)`;
    });
</script>
@endpush
