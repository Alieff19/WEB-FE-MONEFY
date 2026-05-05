@extends('layouts.app')

@section('title', 'Monefy - Manage Bills')

@push('styles')
<style>
        body {
            background-color: #F4F6F8 !important;
        }
        .form-control-custom {
            background-color: #FFFFFF;
            border: 2px solid transparent;
            border-radius: 14px;
            padding: 1rem 1.25rem;
            color: var(--text-dark);
            font-weight: 500;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
        }
        .form-control-custom:focus {
            background-color: white;
            border-color: var(--primary-purple);
            box-shadow: 0 4px 15px rgba(106, 76, 255, 0.1);
            outline: none;
        }
        .form-control-custom::placeholder {
            color: #94A3B8;
        }
        .form-label-custom {
            color: #64748B;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
    </style>
@endpush

@section('content')
<!-- Main Bills Container - Web Layout -->
    <div class="container my-5">
        <div class="row g-5">
            
            <!-- Left Info Panel -->
            <div class="col-lg-4">
                <div class="mb-4">
                    <h3 class="fw-bold mb-3" style="color: var(--primary-purple);">
                         <i class="bi bi-receipt me-2"></i> Upcoming Bills
                    </h3>
                    <p class="text-muted" style="line-height: 1.8;">
                        Keep track of your recurring payments such as Internet, Electricity, and Streaming services so you never miss a due date.
                    </p>
                </div>

                <!-- Info Card -->
                <div class="p-4 rounded-4 mt-5 text-white" style="background: var(--gradient-card); box-shadow: 0 10px 30px rgba(98, 66, 232, 0.3);">
                    <h5 class="fw-bold d-flex align-items-center mb-3">
                        <i class="bi bi-bell fs-3 me-3"></i> 
                        Smart Notification
                    </h5>
                    <p class="mb-0 small" style="opacity: 0.9;">
                        We will remind you 3 days prior to any of your saved Due Dates, ensuring you avoid any late fees.
                    </p>
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="col-lg-8">
                <div class="bg-white p-5 rounded-4" style="box-shadow: 0 6px 35px rgba(0,0,0,0.03);">
                    <h4 class="fw-bold mb-5" style="color: var(--text-dark);">Bill Details</h4>
                    
                    <form class="api-form" action="{{ Route::has('bills.store') ? route('bills.store') : '#' }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <!-- Biller Name -->
                            <div class="col-md-6">
                                <label class="form-label-custom">Biller Name</label>
                                <input type="text" name="biller_name" class="form-control form-control-custom" placeholder="e.g., Netflix, PLN, Indihome" required>
                            </div>

                            <!-- Initial ID -->
                            <div class="col-md-6">
                                <label class="form-label-custom">Customer ID / VA</label>
                                <input type="text" name="customer_id" class="form-control form-control-custom" placeholder="e.g., 001239845" required>
                            </div>

                            <!-- Amount Due -->
                            <div class="col-md-6">
                                <label class="form-label-custom">Amount Due</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 fw-bold px-4" style="border-radius: 14px 0 0 14px; color: var(--primary-purple);">Rp</span>
                                    <input type="number" name="amount" class="form-control form-control-custom" style="border-radius: 0 14px 14px 0;" placeholder="e.g., 150000" required>
                                </div>
                            </div>

                            <!-- Due Date -->
                            <div class="col-md-6">
                                <label class="form-label-custom">Due Date</label>
                                <input type="date" name="due_date" class="form-control form-control-custom text-muted" required>
                            </div>

                            <!-- Billing Cycle -->
                            <div class="col-md-12">
                                <label class="form-label-custom">Billing Cycle</label>
                                <select name="billing_cycle" class="form-select form-control-custom text-muted" required>
                                    <option value="monthly" selected>Monthly</option>
                                    <option value="yearly">Yearly</option>
                                    <option value="once">One-Time Payment</option>
                                </select>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-5" style="opacity: 0.1;">

                        <!-- Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('home') }}" class="btn btn-link text-decoration-none text-muted fw-bold">
                                <i class="bi bi-arrow-left me-1"></i> Cancel
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary-custom px-5"><i class="bi bi-save me-2"></i> Save Bill</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection
