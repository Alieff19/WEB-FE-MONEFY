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
        <div class="modal-content rounded-4 border-0 shadow" style="overflow:hidden;">
          <div class="modal-header modal-header-dynamic header-expense" id="modalHeader">
            <h5 class="modal-title fw-bold" id="addTransactionModalLabel" style="color:var(--text-dark);">Add Transaction</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4 pt-2">

            {{-- ── Type Selector (3 custom buttons) ── --}}
            <div class="d-flex gap-2 mb-4" id="txTypeSelector">
              <button type="button" class="tx-type-btn flex-fill active" id="btn-expense" data-type="expense">
                <i class="bi bi-arrow-up-circle-fill"></i>
                <span>Expense</span>
              </button>
              <button type="button" class="tx-type-btn flex-fill" id="btn-income" data-type="income">
                <i class="bi bi-arrow-down-circle-fill"></i>
                <span>Income</span>
              </button>
              <button type="button" class="tx-type-btn flex-fill" id="btn-transfer" data-type="transfer">
                <i class="bi bi-arrow-left-right"></i>
                <span>Transfer</span>
              </button>
            </div>

            {{-- ── Form ── --}}
            <form class="api-form" id="addTransactionForm"
                  action="{{ route('transaction.store') }}" method="POST">
              @csrf
              <input type="hidden" name="type" id="transactionTypeValue" value="expense">

              {{-- Amount --}}
              <div class="mb-3">
                <label class="form-label fw-semibold text-muted small">Amount</label>
                <div class="input-group">
                  <span class="input-group-text bg-white border-end-0 fw-bold"
                        style="border-radius:12px 0 0 12px; color:var(--text-dark);">Rp</span>
                  <input type="text" inputmode="numeric" name="amount" id="txAmount"
                         class="form-control border-start-0 fs-3 fw-bold shadow-none"
                         placeholder="0"
                         style="border-radius:0 12px 12px 0; color:var(--primary-purple);"
                         autocomplete="off" required>
                </div>
              </div>

              {{-- Title --}}
              <div class="mb-3">
                <label class="form-label fw-semibold text-muted small">Title</label>
                <input type="text" name="title" id="txTitle"
                       class="form-control shadow-none"
                       placeholder="e.g. Lunch, Monthly salary"
                       style="border-radius:12px; padding:0.8rem 1.2rem;" required>
              </div>

              {{-- Category --}}
              <div class="mb-3">
                <label class="form-label fw-semibold text-muted small">Category</label>
                <select name="category" id="txCategory"
                        class="form-select shadow-none"
                        style="border-radius:12px; padding:0.8rem 1.2rem;" required>
                  <option value="" disabled selected>Select Category</option>
                  <optgroup label="Expense">
                    <option value="Food &amp; Drink">🍔 Food &amp; Drink</option>
                    <option value="Transportation">🚗 Transportation</option>
                    <option value="Entertainment">🎮 Entertainment</option>
                    <option value="Shopping">🛍️ Shopping</option>
                    <option value="Bills">🧾 Bills</option>
                    <option value="Health">💊 Health</option>
                    <option value="Education">📚 Education</option>
                  </optgroup>
                  <optgroup label="Income">
                    <option value="Salary">💼 Salary</option>
                    <option value="Freelance">💻 Freelance</option>
                    <option value="Business">🏪 Business</option>
                    <option value="Investment">📈 Investment</option>
                    <option value="Gift">🎁 Gift</option>
                  </optgroup>
                  <optgroup label="Other">
                    <option value="Transfer">🔄 Transfer</option>
                    <option value="Other">📦 Other</option>
                  </optgroup>
                </select>
              </div>

              {{-- Date --}}
              <div class="mb-3">
                <label class="form-label fw-semibold text-muted small">Date</label>
                <input type="date" name="transaction_date" id="txDate"
                       class="form-control shadow-none"
                       style="border-radius:12px; padding:0.8rem 1.2rem;" required>
              </div>

              {{-- Wallet --}}
              <div class="mb-3">
                <label class="form-label fw-semibold text-muted small" id="walletFromLabel">Wallet</label>
                <select name="wallet_id" id="txWallet"
                        class="form-select shadow-none"
                        style="border-radius:12px; padding:0.8rem 1.2rem;" required>
                  <option value="" disabled selected>Loading wallets...</option>
                </select>
              </div>

              {{-- Transfer To (hidden unless Transfer tab) --}}
              <div class="mb-3" id="toWalletGroup" style="display:none;">
                <label class="form-label fw-semibold text-muted small">Transfer To</label>
                <select name="to_wallet_id" id="txToWallet"
                        class="form-select shadow-none"
                        style="border-radius:12px; padding:0.8rem 1.2rem;">
                  <option value="" disabled selected>Select Destination Wallet</option>
                </select>
                <small class="text-muted">Must be a different wallet.</small>
              </div>

              {{-- Note --}}
              <div class="mb-4">
                <label class="form-label fw-semibold text-muted small">Note <span class="fw-normal text-muted">(optional)</span></label>
                <textarea name="note" id="txNote" rows="2"
                          class="form-control shadow-none"
                          placeholder="Add a note..."
                          style="border-radius:12px; padding:0.8rem 1.2rem; resize:none;"></textarea>
              </div>

              <button type="submit" id="txSubmitBtn"
                      class="btn btn-expense-active w-100 py-3"
                      style="border-radius:16px; font-weight:700; font-size:1rem; border:none;">
                <i class="bi bi-arrow-up-circle-fill me-2"></i>Save Expense
              </button>
            </form>

          </div>
        </div>
      </div>
    </div>

    {{-- ── CSS for type selector buttons ── --}}
    <style>
    .tx-type-btn {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
      padding: 10px 8px;
      border-radius: 14px;
      border: 2px solid #E2E8F0;
      background: #F8F9FA;
      color: #A0AEC0;
      font-weight: 600;
      font-size: 0.82rem;
      cursor: pointer;
      transition: all 0.2s ease;
    }
    .tx-type-btn i { font-size: 1.3rem; }
    .tx-type-btn:hover { border-color: #CBD5E0; color: #718096; }

    /* Active states per type */
    .tx-type-btn.active[data-type="expense"] {
      background: rgba(239,68,68,0.08);
      border-color: var(--expense-red);
      color: var(--expense-red);
    }
    .tx-type-btn.active[data-type="income"] {
      background: rgba(16,185,129,0.08);
      border-color: var(--income-green);
      color: var(--income-green);
    }
    .tx-type-btn.active[data-type="transfer"] {
      background: rgba(106,76,255,0.08);
      border-color: var(--primary-purple);
      color: var(--primary-purple);
    }

    /* Submit button colors */
    .btn-expense-active  { background: var(--expense-red)  !important; color: white !important; }
    .btn-income-active   { background: var(--income-green) !important; color: white !important; }
    .btn-transfer-active { background: var(--primary-purple) !important; color: white !important; }

    .modal-header-dynamic {
        transition: all 0.4s ease;
        border-bottom: 0;
        padding: 1.5rem 1.5rem 0.5rem;
    }
    .header-expense { background: rgba(239,68,68,0.05); }
    .header-income  { background: rgba(16,185,129,0.05); }
    .header-transfer { background: rgba(106,76,255,0.05); }
    .luxury-avatar-frame {
        transition: all 0.3s ease;
    }
    .luxury-avatar-frame:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 8px 25px rgba(124, 76, 255, 0.5) !important;
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

      // ── Type selector buttons ──
      const typeButtons   = document.querySelectorAll('.tx-type-btn');
      const typeInput     = document.getElementById('transactionTypeValue');
      const toWalletGrp   = document.getElementById('toWalletGroup');
      const txToWallet    = document.getElementById('txToWallet');
      const submitBtn     = document.getElementById('txSubmitBtn');
      const walletFromLbl = document.getElementById('walletFromLabel');
      const txCategory    = document.getElementById('txCategory');
      const modalHeader   = document.getElementById('modalHeader');

      const typeConfig = {
        expense:  { icon: 'bi-arrow-up-circle-fill',  label: 'Save Expense',  btnClass: 'btn-expense-active',  walletLbl: 'Wallet', headerClass: 'header-expense' },
        income:   { icon: 'bi-arrow-down-circle-fill', label: 'Save Income',   btnClass: 'btn-income-active',   walletLbl: 'Wallet', headerClass: 'header-income' },
        transfer: { icon: 'bi-arrow-left-right',       label: 'Process Transfer', btnClass: 'btn-transfer-active', walletLbl: 'From Wallet', headerClass: 'header-transfer' },
      };

      function setActiveType(type) {
        // Update hidden input
        typeInput.value = type;

        // Update button active states
        typeButtons.forEach(btn => {
          btn.classList.toggle('active', btn.dataset.type === type);
        });

        // Update submit button style + label
        const cfg = typeConfig[type];
        submitBtn.className = `btn ${cfg.btnClass} w-100 py-3`;
        submitBtn.style.cssText = 'border-radius:16px; font-weight:700; font-size:1rem; border:none; transition: 0.3s;';
        submitBtn.innerHTML = `<i class="bi ${cfg.icon} me-2"></i>${cfg.label}`;

        // Update Header Color
        modalHeader.className = `modal-header modal-header-dynamic ${cfg.headerClass}`;

        // Update wallet label
        walletFromLbl.textContent = cfg.walletLbl;

        // Filter Categories
        const groups = txCategory.querySelectorAll('optgroup');
        let firstAvailableSet = false;
        
        groups.forEach(group => {
            const label = group.getAttribute('label').toLowerCase();
            if (type === 'transfer') {
                if (label === 'other') group.style.display = '';
                else group.style.display = 'none';
            } else {
                if (label === type) {
                    group.style.display = '';
                    firstAvailableSet = true;
                } else if (label === 'other') {
                    group.style.display = '';
                } else {
                    group.style.display = 'none';
                }
            }
        });
        
        const selectedOpt = txCategory.options[txCategory.selectedIndex];
        if (selectedOpt && selectedOpt.parentElement.style.display === 'none') {
            txCategory.value = "";
        }

        // Show/hide Transfer To field
        if (type === 'transfer') {
          toWalletGrp.style.display = '';
          txToWallet.setAttribute('required', 'required');
        } else {
          toWalletGrp.style.display = 'none';
          txToWallet.removeAttribute('required');
        }
      }

      typeButtons.forEach(btn => {
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
</body>
</html>
