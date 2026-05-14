@extends('layouts.app')

@section('title', 'Monefy - Savings')

@push('styles')
<style>
        body {
            background-color: #F4F6F8 !important; /* Soft gray backing matching the image */
        }
        .saving-goal-card {
            background-color: white;
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: var(--text-dark);
            text-decoration: none;
        }
        .saving-goal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(106, 76, 255, 0.1);
        }
        .saving-icon-wrapper {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: var(--primary-purple);
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1rem auto;
            box-shadow: 0 4px 15px rgba(106, 76, 255, 0.3);
        }
        .create-saving-card {
            background-color: #E2E8F0; /* Grey from the image */
            color: var(--primary-purple);
            box-shadow: none;
        }
        .create-saving-card .saving-icon-wrapper {
            box-shadow: none;
        }
    </style>
@endpush

@section('content')
<!-- Main Savings Container - Web Layout -->
    <div class="container my-5">
        <div class="row g-5">
            
            <!-- Left Info Panel (The massive total saving) -->
            <div class="col-lg-4">
                <div class="mb-4 d-flex align-items-center">
                    <a href="{{ route('home') }}" class="text-decoration-none me-3" style="color: var(--primary-purple); font-size: 1.5rem;">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h3 class="fw-bold mb-0" style="color: var(--primary-purple);">Savings</h3>
                </div>

                <div class="bg-white p-5 rounded-4 mt-4 text-center" style="box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                    <h5 class="fw-bold mb-3" style="color: var(--primary-purple);">Total Saving</h5>
                    <h2 class="fw-bold mb-0" style="color: var(--primary-purple); word-wrap: break-word;">{{ $totalSaving ?? 'Rp. 0' }}</h2>
                </div>

                <div class="mt-4 text-muted small p-4 bg-light rounded-4">
                    <p class="mb-0"><i class="bi bi-info-circle me-2"></i> Keep track of your financial goals. Your savings goals are separate from your daily expense wallet.</p>
                </div>
            </div>

            <!-- Right Grid Panel (Saving List) -->
            <div class="col-lg-8">
                <h4 class="fw-bold mb-4" style="color: var(--primary-purple);">Saving list</h4>
                
                <div class="row g-4">
                    @forelse($savings ?? [] as $saving)
                    <div class="col-md-6 col-xl-4">
                        <div class="saving-goal-card">
                            <div class="saving-icon-wrapper">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <h4 class="fw-bold" style="color: var(--primary-purple);">{{ $saving->name }}</h4>
                            <div class="fw-bold text-dark mt-2">Rp {{ number_format((float)($saving->current_amount ?? 0), 0, ',', '.') }}</div>
                            <small class="text-muted" style="font-size: 0.70rem;">of Rp {{ number_format((float)($saving->target_amount ?? 0), 0, ',', '.') }} saving</small>
                        </div>
                    </div>
                    @empty
                    @endforelse

                    <!-- Add New Saving Button -->
                    <div class="col-md-6 col-xl-4">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#createSavingModal" class="saving-goal-card create-saving-card text-decoration-none">
                            <div class="saving-icon-wrapper">
                                <i class="bi bi-plus-lg"></i>
                            </div>
                            <h5 class="fw-bold mt-2" style="color: var(--primary-purple);">Create saving</h5>
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Create Saving Modal -->
    <div class="modal fade" id="createSavingModal" tabindex="-1" aria-labelledby="createSavingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="createSavingModalLabel" style="color: var(--primary-purple);">Create New Saving</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form class="api-form" action="{{ Route::has('savings.store') ? route('savings.store') : '#' }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="savingName" class="form-label text-muted fw-semibold">Goal Name</label>
                            <input type="text" class="form-control" style="border-radius: 10px; padding: 0.8rem;" id="savingName" name="name" placeholder="e.g. Dream Laptop" required>
                        </div>
                        <div class="mb-4">
                            <label for="targetAmount" class="form-label text-muted fw-semibold">Target Amount</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 fw-bold px-3" style="border-radius: 10px 0 0 10px; color: var(--primary-purple);">Rp</span>
                                <input type="number" class="form-control" style="border-radius: 0 10px 10px 0; padding: 0.8rem;" id="targetAmount" name="target_amount" placeholder="15000000" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold" style="background-color: var(--primary-purple); border: none;">Save Goal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
