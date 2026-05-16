<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Monefy')</title>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('node_modules/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    @stack('styles')
</head>
<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="loader-content">
            <div class="loader-circle-dashed"></div>
            <div class="loader-circle"></div>
            <div class="loader-logo">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Monefy Logo">
            </div>
        </div>
    </div>

    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Monefy Logo" height="70" class="me-2">
                Monefy.
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('history') ? 'active' : '' }}" href="{{ route('history') }}"><i class="bi bi-clock-history me-1"></i> History</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('analytic') ? 'active' : '' }}" href="{{ route('analytic') }}"><i class="bi bi-graph-up-arrow me-1"></i> Analytic</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}"><i class="bi bi-person me-1"></i> Profile</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                        <i class="bi bi-plus-circle me-1"></i> Add Transaction
                    </button>
                    <a href="{{ route('profile') }}" class="d-block nav-profile-link">
                        @php 
                            $u = $user ?? session('user'); 
                            $rawAvatar = $u['avatar'] ?? ($u['avatar_url'] ?? null);
                            $finalAvatar = null;
                            if ($rawAvatar) {
                                if (str_starts_with($rawAvatar, 'http')) {
                                    $finalAvatar = $rawAvatar;
                                } else {
                                    $fullApiUrl = config('services.monefy_backend.base_url');
                                    $urlParts = parse_url($fullApiUrl);
                                    $host = ($urlParts['scheme'] ?? 'http') . '://' . ($urlParts['host'] ?? '127.0.0.1') . (isset($urlParts['port']) ? ':' . $urlParts['port'] : '');
                                    $finalAvatar = $host . '/' . ltrim($rawAvatar, '/');
                                }
                            }
                        @endphp
                        <div class="luxury-avatar-frame shadow-sm" style="position: relative; width: 52px; height: 52px; padding: 3px; background: linear-gradient(135deg, #7C4CFF, #FF4757); border-radius: 50%; box-shadow: 0 4px 15px rgba(124, 76, 255, 0.4) !important;">
                            <div class="w-100 h-100 rounded-circle overflow-hidden d-flex align-items-center justify-content-center" style="background: white; padding: 2px;">
                                <div class="w-100 h-100 rounded-circle overflow-hidden d-flex align-items-center justify-content-center" style="background: var(--soft-purple);">
                                    @if($finalAvatar)
                                        <img src="{{ $finalAvatar }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="bi bi-person-fill text-primary" style="font-size: 1.5rem;"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    {{-- Flash Messages --}}
    <div class="position-fixed bottom-0 start-50 translate-middle-x p-3" style="z-index: 1060">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow rounded-4 border-0" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow rounded-4 border-0" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Add Transaction Modal -->
    <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 32px; overflow: hidden; background: #fff;">
          {{-- Header with Dynamic Gradient --}}
          <div class="modal-header-premium p-4 text-center position-relative" id="modalHeaderPremium">
            <div class="modal-close-btn-wrapper">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <h5 class="modal-title fw-bold text-white mb-0" id="addTransactionModalLabel">New Transaction</h5>
            
            {{-- Segmented Type Selector --}}
            <div class="type-segmented-control mt-4">
                <div class="type-segment-glider" id="typeGlider"></div>
                <button type="button" class="type-segment active" data-type="expense" onclick="setActiveType('expense')">Expense</button>
                <button type="button" class="type-segment" data-type="income" onclick="setActiveType('income')">Income</button>
                <button type="button" class="type-segment" data-type="transfer" onclick="setActiveType('transfer')">Transfer</button>
            </div>
          </div>

          <div class="modal-body p-4 pt-0">
            <form class="api-form" id="addTransactionForm" action="{{ route('transaction.store') }}" method="POST">
              @csrf
              <input type="hidden" name="type" id="transactionTypeValue" value="expense">

              {{-- Hero Amount Input --}}
              <div class="hero-amount-section text-center py-4">
                  <span class="currency-label">Rp</span>
                  <input type="text" inputmode="numeric" name="amount" id="txAmount" 
                         class="hero-amount-input" placeholder="0" autocomplete="off" required>
                  <div class="amount-underline" id="amountUnderline"></div>
              </div>

              <div class="row g-3">
                  {{-- Title --}}
                  <div class="col-12">
                    <div class="premium-field-group">
                        <i class="bi bi-pencil-square field-icon"></i>
                        <input type="text" name="title" id="txTitle" class="premium-field-input" placeholder="What's this for?" required>
                    </div>
                  </div>

                  {{-- Category --}}
                  <div class="col-md-6">
                    <div class="premium-field-group">
                        <i class="bi bi-tag field-icon"></i>
                        <select name="category" id="txCategory" class="premium-field-input" required>
                            <option value="" disabled selected>Category</option>
                            <optgroup label="Expense">
                                <option value="Food & Drink">Food & Drink</option>
                                <option value="Transportation">Transportation</option>
                                <option value="Entertainment">Entertainment</option>
                                <option value="Shopping">Shopping</option>
                                <option value="Bills">Bills</option>
                                <option value="Health">Health</option>
                                <option value="Education">Education</option>
                            </optgroup>
                            <optgroup label="Income">
                                <option value="Salary">Salary</option>
                                <option value="Freelance">Freelance</option>
                                <option value="Business">Business</option>
                                <option value="Investment">Investment</option>
                                <option value="Gift">Gift</option>
                            </optgroup>
                            <optgroup label="Other">
                                <option value="Transfer">Transfer</option>
                                <option value="Other">Other</option>
                            </optgroup>
                        </select>
                    </div>
                  </div>

                  {{-- Date --}}
                  <div class="col-md-6">
                    <div class="premium-field-group">
                        <i class="bi bi-calendar3 field-icon"></i>
                        <input type="date" name="transaction_date" id="txDate" class="premium-field-input" required>
                    </div>
                  </div>

                  {{-- Wallet --}}
                  <div class="col-12">
                    <div class="premium-field-group">
                        <i class="bi bi-wallet2 field-icon"></i>
                        <select name="wallet_id" id="txWallet" class="premium-field-input" required>
                            <option value="" disabled selected>Select Wallet</option>
                        </select>
                    </div>
                  </div>

                  {{-- Transfer To (hidden unless Transfer tab) --}}
                  <div class="col-12" id="toWalletGroup" style="display:none;">
                    <div class="premium-field-group border-primary">
                        <i class="bi bi-arrow-right-circle field-icon text-primary"></i>
                        <select name="to_wallet_id" id="txToWallet" class="premium-field-input">
                            <option value="" disabled selected>Transfer To...</option>
                        </select>
                    </div>
                  </div>

                  {{-- Note --}}
                  <div class="col-12">
                    <div class="premium-field-group align-items-start py-2">
                        <i class="bi bi-chat-left-text field-icon mt-2"></i>
                        <textarea name="note" id="txNote" rows="2" class="premium-field-input" placeholder="Add a note... (optional)" style="resize:none;"></textarea>
                    </div>
                  </div>
              </div>

              <button type="submit" id="txSubmitBtn" class="btn-premium-submit mt-4 w-100">
                <span>Save Transaction</span>
                <i class="bi bi-arrow-right-short fs-4"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- ── CSS for Premium Transaction Modal ── --}}
    <style>
    .modal-header-premium {
        background: linear-gradient(135deg, #EF4444 0%, #B91C1C 100%); /* Default Expense */
        padding-bottom: 3rem !important;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .modal-header-premium.bg-income { background: linear-gradient(135deg, #10B981 0%, #059669 100%); }
    .modal-header-premium.bg-transfer { background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%); }

    .modal-close-btn-wrapper { position: absolute; top: 20px; right: 20px; z-index: 10; }

    .type-segmented-control {
        display: flex;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 4px;
        position: relative;
        margin: 0 10%;
    }
    .type-segment {
        flex: 1;
        border: none;
        background: transparent;
        color: white;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 8px 0;
        position: relative;
        z-index: 2;
        transition: 0.3s;
        opacity: 0.8;
    }
    .type-segment.active { opacity: 1; }
    .type-segment-glider {
        position: absolute;
        height: calc(100% - 8px);
        width: calc(33.333% - 4px);
        background: white;
        border-radius: 12px;
        top: 4px;
        left: 4px;
        z-index: 1;
        transition: all 0.4s cubic-bezier(0.18, 0.89, 0.32, 1.28);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .hero-amount-section {
        margin-top: -20px;
        background: white;
        border-radius: 24px 24px 0 0;
        position: relative;
        z-index: 5;
    }
    .hero-amount-input {
        width: 100%;
        border: none;
        text-align: center;
        font-size: 3rem;
        font-weight: 800;
        color: var(--text-dark);
        outline: none;
        padding: 0 1rem;
    }
    .currency-label {
        font-size: 1.2rem;
        font-weight: 800;
        color: #94A3B8;
        display: block;
        margin-bottom: -5px;
    }
    .amount-underline {
        height: 4px;
        width: 60px;
        background: #EF4444;
        margin: 5px auto 0;
        border-radius: 2px;
        transition: 0.5s;
    }

    .premium-field-group {
        display: flex;
        align-items: center;
        background: #F8FAFC;
        border: 2px solid #F1F5F9;
        border-radius: 18px;
        padding: 0.5rem 1.2rem;
        transition: 0.3s;
    }
    .premium-field-group:focus-within {
        background: white;
        border-color: #CBD5E0;
        box-shadow: 0 10px 20px rgba(0,0,0,0.03);
    }
    .field-icon { font-size: 1.2rem; color: #94A3B8; margin-right: 12px; }
    .premium-field-input {
        width: 100%;
        border: none;
        background: transparent;
        padding: 0.6rem 0;
        font-weight: 600;
        color: var(--text-dark);
        outline: none;
        font-size: 0.95rem;
    }
    .premium-field-input::placeholder { color: #CBD5E0; font-weight: 400; }

    .btn-premium-submit {
        background: #EF4444;
        color: white;
        border: none;
        border-radius: 20px;
        padding: 1.2rem;
        font-weight: 800;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.4s;
        box-shadow: 0 15px 30px rgba(239, 68, 68, 0.3);
    }
    .btn-premium-submit:hover { transform: translateY(-4px); filter: brightness(1.1); }
    </style>

    {{-- ── Script: Type selector + Wallet loader + Amount formatter ── --}}
    <script>
    (function () {

      // ── Set today's date as default ──
      const dateInput = document.getElementById('txDate');
      if (dateInput) dateInput.value = new Date().toISOString().split('T')[0];

      // ── Amount: format with thousand separator (10.000, 1.000.000) ──
      const amountInput = document.getElementById('txAmount');
      if (amountInput) {
        amountInput.addEventListener('input', function () {
          // Strip non-numeric
          let raw = this.value.replace(/[^0-9]/g, '');
          if (!raw) { this.value = ''; return; }
          // Format with dot thousands (Indonesian)
          this.value = parseInt(raw, 10).toLocaleString('id-ID').replace(/,/g, '.');
        });
      }

      // ── Type selector logic ──
      const typeSegments  = document.querySelectorAll('.type-segment');
      const typeInput     = document.getElementById('transactionTypeValue');
      const toWalletGrp   = document.getElementById('toWalletGroup');
      const txToWallet    = document.getElementById('txToWallet');
      const submitBtn     = document.getElementById('txSubmitBtn');
      const txCategory    = document.getElementById('txCategory');
      const modalHeader   = document.getElementById('modalHeaderPremium');
      const typeGlider    = document.getElementById('typeGlider');
      const amountLine    = document.getElementById('amountUnderline');
      // amountInput is already declared above

      const config = {
        expense:  { color: '#EF4444', class: '',          glider: '4px',    title: 'Save Expense',    activeSegment: 0 },
        income:   { color: '#10B981', class: 'bg-income',   glider: 'calc(33.333% + 2px)', title: 'Save Income', activeSegment: 1 },
        transfer: { color: '#6366F1', class: 'bg-transfer', glider: 'calc(66.666% + 2px)', title: 'Process Transfer', activeSegment: 2 }
      };

      window.setActiveType = function(type) {
        const cfg = config[type];
        
        // Update state
        typeInput.value = type;
        
        // Update Glider & Buttons
        typeGlider.style.left = cfg.glider;
        typeSegments.forEach((s, idx) => {
            s.classList.toggle('active', idx === cfg.activeSegment);
            s.style.color = idx === cfg.activeSegment ? cfg.color : 'white';
        });

        // Update Theme
        modalHeader.className = `modal-header-premium p-4 text-center position-relative ${cfg.class}`;
        amountLine.style.background = cfg.color;
        amountInput.style.color = cfg.color;
        submitBtn.style.background = cfg.color;
        submitBtn.style.boxShadow = `0 15px 30px ${cfg.color}44`;
        submitBtn.querySelector('span').innerText = cfg.title;

        // Filter Categories
        const groups = txCategory.querySelectorAll('optgroup');
        groups.forEach(group => {
            const label = group.getAttribute('label').toLowerCase();
            if (type === 'transfer') group.style.display = label === 'other' ? '' : 'none';
            else group.style.display = (label === type || label === 'other') ? '' : 'none';
        });
        
        if (txCategory.selectedOptions[0]?.parentElement.style.display === 'none') txCategory.value = "";

        // Show/hide Transfer To
        toWalletGrp.style.display = type === 'transfer' ? '' : 'none';
        if (type === 'transfer') txToWallet.setAttribute('required', 'required');
        else txToWallet.removeAttribute('required');
      }

      typeSegments.forEach(btn => {
        btn.addEventListener('click', () => setActiveType(btn.dataset.type));
      });

      // ── Wallet dropdown loader ──
      const txWallet   = document.getElementById('txWallet');
      let allWallets   = [];

      const buildOptions = (sel, wallets, excludeId = null) => {
        sel.innerHTML = '<option value="" disabled selected>Select Wallet</option>';
        if (!wallets.length) {
          sel.innerHTML = '<option value="" disabled selected>⚠️ No wallets found</option>';
          return;
        }
        wallets.forEach(w => {
          if (excludeId && String(w.id) === String(excludeId)) return;
          const opt = document.createElement('option');
          opt.value = w.id;
          opt.textContent = `${w.name_wallet} — Rp ${Number(w.balance).toLocaleString('id-ID')}`;
          sel.appendChild(opt);
        });
      };

      txWallet && txWallet.addEventListener('change', function () {
        buildOptions(txToWallet, allWallets, this.value);
      });

      const modal = document.getElementById('addTransactionModal');
      modal && modal.addEventListener('show.bs.modal', async function () {
        if (allWallets.length > 0) return;
        try {
          const res  = await fetch('{{ route("wallet.list") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          });
          const json = await res.json();
          allWallets = json.data ?? [];
          buildOptions(txWallet, allWallets);
          buildOptions(txToWallet, allWallets);
        } catch (e) {
          console.error('Failed to load wallets:', e);
        }
      });

      modal && modal.addEventListener('hidden.bs.modal', function () {
        document.getElementById('addTransactionForm').reset();
        setActiveType('expense');
      });

    })();
    </script>

    <!-- Scripts -->
    <script src="{{ asset('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/api.js') }}"></script>
    @stack('scripts')
    <!-- Preloader Script -->
    <script>
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            setTimeout(() => {
                preloader.classList.add('preloader-hidden');
            }, 300); // Reduced delay for better perceived performance
        });

        // Handle browser back/forward cache
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                const preloader = document.getElementById('preloader');
                preloader.classList.add('preloader-hidden');
            }
        });

        // Trigger preloader on menu transition (only for real page reloads)
        window.addEventListener('beforeunload', function(e) {
            // Check if the current active element is a link that shouldn't trigger preloader
            const activeEl = document.activeElement;
            if (activeEl && activeEl.tagName === 'A') {
                const href = activeEl.getAttribute('href');
                if (!href || 
                    href.startsWith('#') || 
                    href.startsWith('javascript:') || 
                    activeEl.hasAttribute('data-bs-toggle') || 
                    activeEl.classList.contains('no-preloader') ||
                    activeEl.getAttribute('target') === '_blank') {
                    return;
                }
            }
            
            const preloader = document.getElementById('preloader');
            preloader.classList.remove('preloader-hidden');
        });

        // Fallback: If preloader stays too long (e.g. download links or canceled reloads)
        // hide it after 5 seconds
        const preloaderTimeout = setTimeout(() => {
            const preloader = document.getElementById('preloader');
            if (preloader) preloader.classList.add('preloader-hidden');
        }, 5000);
        
        window.addEventListener('load', () => clearTimeout(preloaderTimeout));
    </script>
</body>
</html>
