<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Monefy')</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
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
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('ai.index') ? 'active' : '' }}" href="{{ route('ai.index') }}"><i class="bi bi-robot me-1 text-primary"></i> AI Assistant</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}"><i class="bi bi-person me-1"></i> Profile</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <!-- Notification Bell Dropdown -->
                    <div class="dropdown me-1" id="billNotificationDropdown">
                        <button class="btn btn-link position-relative p-2 text-secondary hover-primary animate-pulse-mic" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="outline: none; box-shadow: none; border: none; background: transparent;">
                            <i class="bi bi-bell-fill fs-4" style="color: #7C4CFF;"></i>
                            <span id="billBadgeCount" class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger d-none" style="padding: 4px 6px; font-size: 0.65rem;">
                                0
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-2 border-0 shadow-lg" style="width: 320px; border-radius: 20px; font-size: 0.9rem; z-index: 1050; margin-top: 10px;">
                            <li class="px-3 py-2 fw-bold text-dark border-bottom d-flex justify-content-between align-items-center">
                                <span>Tagihan Mendatang</span>
                                <span class="badge" id="dropdownUnpaidCount" style="color: #7C4CFF !important; background-color: #F3F0FF !important; font-size: 0.75rem;">0 Tagihan</span>
                            </li>
                            <div id="dropdownBillsList" style="max-height: 250px; overflow-y: auto;">
                                <li class="text-center py-3 text-muted">Memuat tagihan...</li>
                            </div>
                            <li class="text-center pt-2 border-top">
                                <a class="dropdown-item fw-bold" href="{{ route('bills') }}" style="border-radius: 12px; color: #7C4CFF;">Lihat Semua Tagihan</a>
                            </li>
                        </ul>
                    </div>

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
                                    if (str_contains($rawAvatar, '/storage/v1/s3/')) {
                                        $rawAvatar = str_replace('/storage/v1/s3/', '/storage/v1/object/public/', $rawAvatar);
                                    }
                                    $finalAvatar = $rawAvatar . (str_contains($rawAvatar, '?') ? '&' : '?') . 'v=' . time();
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
              <input type="hidden" name="_method" id="transactionFormMethod" value="POST">
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

    /* ─── BILL NOTIFICATION STYLES ─── */
    .bill-notification-toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 360px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border: 1px solid rgba(124, 76, 255, 0.2);
        border-radius: 20px;
        padding: 1.2rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(124, 76, 255, 0.05);
        z-index: 2000;
        transform: translateY(150%);
        opacity: 0;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .bill-notification-toast.show {
        transform: translateY(0);
        opacity: 1;
    }
    .dropdown-bill-item {
        transition: all 0.2s;
        border-radius: 12px;
        padding: 8px 12px;
    }
    .dropdown-bill-item:hover {
        background-color: #F8FAFC;
    }
    .hover-primary:hover i {
        color: #7C4CFF !important;
        transform: scale(1.1);
        transition: all 0.2s;
    }
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

      let transactionEditMode = false;
      const addTransactionModal = document.getElementById('addTransactionModal');
      const addTransactionTitle = document.getElementById('addTransactionModalLabel');
      const addTransactionForm = document.getElementById('addTransactionForm');
      const transactionFormMethodInput = document.getElementById('transactionFormMethod');

      const editModeReset = function() {
          transactionEditMode = false;
          addTransactionForm.setAttribute('method', 'POST');
          transactionFormMethodInput.value = 'POST';
          addTransactionForm.setAttribute('action', '{{ route('transaction.store') }}');
          addTransactionTitle.innerText = 'New Transaction';
          submitBtn.querySelector('span').innerText = 'Save Transaction';
          typeSegments.forEach(btn => btn.disabled = false);
          setActiveType('expense');
      };

      const ensureWalletsLoaded = async function() {
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
      };

      window.openEditTransactionModal = async function(data) {
          await ensureWalletsLoaded();

          transactionEditMode = true;
          addTransactionTitle.innerText = 'Edit Transaction';
          addTransactionForm.setAttribute('action', `/transactions/${data.id}`);
          addTransactionForm.setAttribute('method', 'PUT');
          transactionFormMethodInput.value = 'PUT';
          typeSegments.forEach(btn => btn.disabled = true);

          if (data.type) {
              setActiveType(data.type);
          }

          txAmount.value = Number(data.rawAmount || data.amount || 0).toLocaleString('id-ID');
          txTitle.value = data.title || '';
          txCategory.value = data.category || '';
          txDate.value = data.transactionDate ? data.transactionDate.split(' ')[0] : '';
          txWallet.value = data.walletId || '';
          txWallet.dispatchEvent(new Event('change'));
          txToWallet.value = data.toWalletId || '';
          txNote.value = data.note || '';

          const bsModal = new bootstrap.Modal(addTransactionModal);
          bsModal.show();
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
        editModeReset();
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

    <!-- Floating Bill Toast Notification -->
    <div id="billToastNotification" class="bill-notification-toast">
        <div class="d-flex align-items-start gap-3">
            <div class="toast-icon-wrapper text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; min-width: 42px; background-color: #FFA502 !important;">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-bold text-dark mb-1">Tagihan Perlu Dibayar!</div>
                <div id="billToastText" class="small text-muted mb-2">Tagihan Wifi Indihome sebesar Rp 350.000 jatuh tempo hari ini.</div>
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-sm btn-light border" id="btnDismissToast" style="border-radius: 8px; font-size: 0.75rem; font-weight: 600;">Nanti</button>
                    <a href="{{ route('bills') }}" class="btn btn-sm btn-primary text-white" style="background-color: #7C4CFF; border-color: #7C4CFF; border-radius: 8px; font-size: 0.75rem; font-weight: 600; text-decoration: none;">Bayar Sekarang</a>
                </div>
            </div>
            <button type="button" class="btn-close ms-auto" id="btnCloseToast" style="font-size: 0.75rem; outline: none; box-shadow: none;"></button>
        </div>
    </div>

    <!-- Script: Global Bill Reminders -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const billBadgeCount = document.getElementById('billBadgeCount');
        const dropdownUnpaidCount = document.getElementById('dropdownUnpaidCount');
        const dropdownBillsList = document.getElementById('dropdownBillsList');
        const billToastNotification = document.getElementById('billToastNotification');
        const billToastText = document.getElementById('billToastText');
        const btnCloseToast = document.getElementById('btnCloseToast');
        const btnDismissToast = document.getElementById('btnDismissToast');

        if (!billBadgeCount) return;

        // Fetch bills from our new AJAX endpoint
        fetch('{{ route("bills") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch bills');
            return response.json();
        })
        .then(bills => {
            const unpaid = bills.filter(b => b.status === 'unpaid');
            
            // 1. Update Badge
            if (unpaid.length > 0) {
                billBadgeCount.textContent = unpaid.length;
                billBadgeCount.classList.remove('d-none');
                dropdownUnpaidCount.textContent = `${unpaid.length} Tagihan`;
            } else {
                billBadgeCount.classList.add('d-none');
                dropdownUnpaidCount.textContent = `0 Tagihan`;
            }

            // 2. Populate Dropdown
            dropdownBillsList.innerHTML = '';
            if (unpaid.length === 0) {
                dropdownBillsList.innerHTML = `
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-patch-check-fill text-success fs-3 mb-2 d-block"></i>
                        <span class="small fw-semibold">Semua tagihan lunas!</span>
                    </div>
                `;
            } else {
                unpaid.forEach(bill => {
                    const dueDate = new Date(bill.due_date);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    dueDate.setHours(0, 0, 0, 0);
                    
                    const timeDiff = dueDate.getTime() - today.getTime();
                    const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
                    
                    let dueLabel = '';
                    let dueClass = 'text-warning';
                    
                    if (daysDiff === 0) {
                        dueLabel = 'Jatuh tempo hari ini';
                        dueClass = 'text-danger fw-bold';
                    } else if (daysDiff < 0) {
                        dueLabel = `Terlewat ${Math.abs(daysDiff)} hari`;
                        dueClass = 'text-danger fw-bold';
                    } else {
                        dueLabel = `Jatuh tempo ${daysDiff} hari lagi`;
                        dueClass = 'text-muted';
                    }

                    dropdownBillsList.innerHTML += `
                        <div class="dropdown-bill-item d-flex align-items-center justify-content-between p-2 mx-1 my-1">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; color: #7C4CFF;">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                </div>
                                <div class="text-start">
                                    <div class="fw-bold text-dark" style="font-size: 0.85rem;">${bill.provider}</div>
                                    <div class="small ${dueClass}" style="font-size: 0.75rem;">${dueLabel}</div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-dark" style="font-size: 0.85rem;">Rp ${parseInt(bill.amount).toLocaleString('id-ID')}</div>
                            </div>
                        </div>
                    `;
                });
            }

            // 3. Toast Alert check (if any bill is due today or tomorrow or overdue, and not shown this session)
            const urgentBills = unpaid.filter(b => {
                const dueDate = new Date(b.due_date);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                dueDate.setHours(0, 0, 0, 0);
                const daysDiff = Math.ceil((dueDate - today) / (1000 * 3600 * 24));
                return daysDiff <= 1; // Due today, tomorrow, or overdue
            });

            if (urgentBills.length > 0 && !sessionStorage.getItem('bill_toast_shown')) {
                const topBill = urgentBills[0];
                const dueDate = new Date(topBill.due_date);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                dueDate.setHours(0, 0, 0, 0);
                const daysDiff = Math.ceil((dueDate - today) / (1000 * 3600 * 24));

                let statusStr = '';
                if (daysDiff === 0) statusStr = 'jatuh tempo hari ini';
                else if (daysDiff < 0) statusStr = 'telah terlewat jatuh tempo';
                else statusStr = 'jatuh tempo besok';

                billToastText.innerHTML = `Tagihan <b>${topBill.provider}</b> sebesar <b>Rp ${parseInt(topBill.amount).toLocaleString('id-ID')}</b> ${statusStr}.`;
                
                setTimeout(() => {
                    billToastNotification.classList.add('show');
                    playBillNotificationSound();
                    sessionStorage.setItem('bill_toast_shown', 'true');
                }, 1500);
            }
        })
        .catch(err => console.warn('Notification center error:', err));

        const dismissToast = () => {
            billToastNotification.classList.remove('show');
        };

        if (btnCloseToast) btnCloseToast.addEventListener('click', dismissToast);
        if (btnDismissToast) btnDismissToast.addEventListener('click', dismissToast);
    });

    function playBillNotificationSound() {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc1 = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();
            osc1.connect(gainNode);
            gainNode.connect(audioCtx.destination);
            
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
            gainNode.gain.setValueAtTime(0.08, audioCtx.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.3);
            
            osc1.start(audioCtx.currentTime);
            osc1.stop(audioCtx.currentTime + 0.3);

            setTimeout(() => {
                const osc2 = audioCtx.createOscillator();
                const gainNode2 = audioCtx.createGain();
                osc2.connect(gainNode2);
                gainNode2.connect(audioCtx.destination);
                
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(880, audioCtx.currentTime); // A5
                gainNode2.gain.setValueAtTime(0.08, audioCtx.currentTime);
                gainNode2.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.4);
                
                osc2.start(audioCtx.currentTime);
                osc2.stop(audioCtx.currentTime + 0.4);
            }, 140);
        } catch (e) {
            console.warn('AudioContext sound blocked or unsupported:', e);
        }
    }
    </script>
</body>
</html>
