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
                <div class="d-flex">
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                        <i class="bi bi-plus-circle me-1"></i> Add Transaction
                    </button>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Add Transaction Modal -->
    <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow" style="overflow: hidden;">
          <div class="modal-header border-0 bg-transparent pb-0 p-4">
            <h5 class="modal-title fw-bold" id="addTransactionModalLabel" style="color: var(--text-dark);">Add Transaction</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4 pt-3">
             <ul class="nav nav-pills transaction-type mb-4 d-flex justify-content-center" id="transactionTypeTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active px-4 py-2 rounded-pill" id="expense-tab" data-bs-toggle="pill" data-bs-target="#expense" type="button" role="tab" style="font-weight: 600;">Expense</button>
                </li>
                <li class="nav-item ms-2" role="presentation">
                    <button class="nav-link px-4 py-2 rounded-pill" id="income-tab" data-bs-toggle="pill" data-bs-target="#income" type="button" role="tab" style="font-weight: 600;">Income</button>
                </li>
             </ul>
             <form class="api-form" action="{{ Route::has('transaction.store') ? route('transaction.store') : '#' }}" method="POST">
                  @csrf
                  <input type="hidden" name="type" id="transactionTypeValue" value="expense">
                  <div class="mb-3">
                      <label class="form-label fw-semibold text-muted small">Amount</label>
                      <div class="input-group">
                          <span class="input-group-text bg-white border-end-0 fw-bold" style="border-radius: 12px 0 0 12px; color: var(--text-dark);">Rp</span>
                          <input type="number" name="amount" class="form-control border-start-0 fs-3 fw-bold shadow-none" placeholder="0" style="border-radius: 0 12px 12px 0; color: var(--primary-purple);" required>
                      </div>
                  </div>
                  <div class="mb-3">
                      <label class="form-label fw-semibold text-muted small">Category</label>
                      <select name="category_id" class="form-select shadow-none" id="transactionCategory" style="border-radius: 12px; padding: 0.8rem 1.2rem; color: var(--text-dark);" required>
                          <option selected>Select Category</option>
                          <option value="1">Food & Drink</option>
                          <option value="2">Transportation</option>
                          <option value="3">Entertainment</option>
                          <option value="4">Shopping</option>
                          <option value="5">Bills</option>
                      </select>
                  </div>
                  <div class="mb-4">
                      <label class="form-label fw-semibold text-muted small">Wallet</label>
                      <select name="wallet_id" class="form-select shadow-none" style="border-radius: 12px; padding: 0.8rem 1.2rem; color: var(--text-dark);" required>
                          <option selected>Select Wallet</option>
                          <option value="gopay">GoPay</option>
                          <option value="shopeepay">ShopeePay</option>
                          <option value="cash">Cash</option>
                      </select>
                  </div>
                  <button type="submit" class="btn btn-primary-custom w-100 py-3 mt-2" style="border-radius: 16px;">Save Transaction</button>
             </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Wallet Modal -->
    <div class="modal fade" id="addWalletModal" tabindex="-1" aria-labelledby="addWalletModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow" style="overflow: hidden;">
          <div class="modal-header border-0 bg-transparent pb-0 p-4">
            <h5 class="modal-title fw-bold" id="addWalletModalLabel" style="color: var(--text-dark);">Add a New Wallet</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4 pt-3">
             <form class="api-form" action="{{ Route::has('wallet.store') ? route('wallet.store') : '#' }}" method="POST">
                  @csrf
                  <div class="mb-3">
                      <label class="form-label fw-semibold text-muted small">Wallet Name</label>
                      <div class="position-relative">
                          <input type="text" name="name" class="form-control shadow-none" placeholder="e.g. Dana, BCA" style="border-radius: 12px; padding: 0.8rem 1.2rem 0.8rem 2.8rem; color: var(--text-dark);" required>
                          <i class="bi bi-wallet2 position-absolute text-muted" style="left: 15px; top: 12px; font-size: 1.1rem;"></i>
                      </div>
                  </div>
                  <div class="mb-4">
                      <label class="form-label fw-semibold text-muted small">Initial Balance</label>
                      <div class="input-group">
                          <span class="input-group-text bg-white border-end-0 fw-bold" style="border-radius: 12px 0 0 12px; color: var(--text-dark);">Rp</span>
                          <input type="number" name="balance" class="form-control border-start-0 fs-4 fw-bold shadow-none" placeholder="0" style="border-radius: 0 12px 12px 0; color: var(--income-green);" required>
                      </div>
                  </div>
                  <button type="submit" class="btn btn-primary-custom w-100 py-3 mt-2" style="border-radius: 16px; background-color: var(--income-green); border-color: var(--income-green);">Add Wallet</button>
             </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/api.js') }}"></script>
    @stack('scripts')
</body>
</html>
