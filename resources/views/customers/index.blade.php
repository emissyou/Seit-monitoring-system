@extends('layouts.app')
@section('title', 'Customers')
@section('content')
<div class="container" id="customer">

    {{-- Header --}}
    <div class="d-flex flex-row justify-content-between align-items-center pt-4">
        <div>
            <h1 class="fs-4 fw-semibold">Customers</h1>
            <p class="text-muted mb-0">Manage customer information and credits</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" id="archivedTabBtn" onclick="toggleView('archived')">
                <i class="bi bi-archive me-1"></i> Archived
                @if($archivedCustomers->total() > 0)
                    <span class="badge bg-secondary ms-1">{{ $archivedCustomers->total() }}</span>
                @endif
            </button>
            <button class="btn btn-primary" id="showFormBtn" onclick="showForm()">
                <i class="bi bi-plus-lg me-1"></i> Add Customer
            </button>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Add Customer Form --}}
    <div class="border rounded-3 p-4 mt-4" id="customerForm" style="display: none;">
        <h6 class="fw-semibold mb-3">Add New Customer</h6>
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            <div class="d-flex flex-wrap gap-3">
                <div class="flex-grow-1" style="min-width: 200px;">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('fname') is-invalid @enderror"
                           name="fname" value="{{ old('fname') }}" placeholder="First Name">
                    @error('fname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="flex-grow-1" style="min-width: 200px;">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control @error('mname') is-invalid @enderror"
                           name="mname" value="{{ old('mname') }}" placeholder="Middle Name (optional)">
                    @error('mname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="flex-grow-1" style="min-width: 200px;">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('lname') is-invalid @enderror"
                           name="lname" value="{{ old('lname') }}" placeholder="Last Name">
                    @error('lname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="d-flex flex-wrap gap-3 mt-3">
                <div class="flex-grow-1" style="min-width: 200px;">
                    <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('contact_number') is-invalid @enderror"
                           name="contact_number" value="{{ old('contact_number') }}" placeholder="09XX XXX XXXX">
                    @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="flex-grow-1" style="min-width: 200px;">
                    <label class="form-label">Address <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                           name="address" value="{{ old('address') }}" placeholder="Address">
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-3 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary" onclick="hideForm()">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Customer</button>
            </div>
        </form>
    </div>


    {{-- ======================== --}}
    {{-- ACTIVE CUSTOMERS TABLE  --}}
    {{-- ======================== --}}
    <div id="activeView">
        {{-- Search & Filter Bar --}}
        <form method="GET" action="{{ route('customers') }}" class="mt-4" id="searchFilterForm">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                {{-- Search input --}}
                <div class="input-group" style="max-width: 340px;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" id="searchInput"
                           class="form-control border-start-0 ps-0"
                           placeholder="Search name, contact, address…"
                           value="{{ $search ?? '' }}"
                           autocomplete="off">
                    @if($search)
                        <button type="button" class="btn btn-outline-secondary"
                                onclick="clearSearch()" title="Clear search">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    @endif
                </div>

                {{-- Balance filter --}}
                <div class="d-flex gap-1">
                    <a href="{{ route('customers', array_merge(request()->except(['balance','page']), ['search' => $search])) }}"
                       class="btn btn-sm {{ !($balance ?? null) ? 'btn-secondary' : 'btn-outline-secondary' }}">
                        All
                    </a>
                    <a href="{{ route('customers', array_merge(request()->except(['balance','page']), ['search' => $search, 'balance' => 'with'])) }}"
                       class="btn btn-sm {{ ($balance ?? null) === 'with' ? 'btn-danger' : 'btn-outline-danger' }}">
                        <i class="bi bi-exclamation-circle me-1"></i>With Balance
                    </a>
                    <a href="{{ route('customers', array_merge(request()->except(['balance','page']), ['search' => $search, 'balance' => 'without'])) }}"
                       class="btn btn-sm {{ ($balance ?? null) === 'without' ? 'btn-success' : 'btn-outline-success' }}">
                        <i class="bi bi-check-circle me-1"></i>No Balance
                    </a>
                </div>

                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-search me-1"></i>Search
                </button>
            </div>
        </form>

        <div class="border rounded-3 p-4 mt-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-semibold">
                    Active Customers
                    @if($search || ($balance ?? null))
                        <span class="text-muted fw-normal fs-6 ms-1">
                            — filtered results
                            @if($search)<span class="badge bg-light text-dark border ms-1">{{ $search }}</span>@endif
                            @if(($balance ?? null) === 'with')<span class="badge bg-danger ms-1">With Balance</span>@endif
                            @if(($balance ?? null) === 'without')<span class="badge bg-success ms-1">No Balance</span>@endif
                        </span>
                    @endif
                </span>
                <span class="text-muted small">
                    Showing {{ $customers->firstItem() ?? 0 }}–{{ $customers->lastItem() ?? 0 }}
                    of {{ $customers->total() }} customers
                </span>
            </div>

            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        <th>Outstanding Balance</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        @php
                            $outstanding = $customer->credits->sum(fn($c) => max(0, $c->amount - $c->amount_paid));
                        @endphp
                        <tr>
                            <td class="text-muted small">{{ $customer->id }}</td>
                            <td class="fw-semibold">
                                {{ $customer->first_name }}
                                {{ $customer->middle_name ? $customer->middle_name . ' ' : '' }}{{ $customer->last_name }}
                            </td>
                            <td>{{ $customer->contact_number }}</td>
                            <td>{{ $customer->address }}</td>
                            <td class="{{ $outstanding > 0 ? 'text-danger fw-semibold' : 'text-success' }}">
                                ₱{{ number_format($outstanding, 2) }}
                            </td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                            type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item view-btn" href="#"
                                               data-id="{{ $customer->id }}"
                                               data-fname="{{ $customer->first_name }}"
                                               data-mname="{{ $customer->middle_name }}"
                                               data-lname="{{ $customer->last_name }}"
                                               data-contact="{{ $customer->contact_number }}"
                                               data-address="{{ $customer->address }}">
                                                <i class="bi bi-eye me-2"></i>View
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item edit-btn" href="#"
                                               data-id="{{ $customer->id }}"
                                               data-fname="{{ $customer->first_name }}"
                                               data-mname="{{ $customer->middle_name }}"
                                               data-lname="{{ $customer->last_name }}"
                                               data-contact="{{ $customer->contact_number }}"
                                               data-address="{{ $customer->address }}">
                                                <i class="bi bi-pencil me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-warning archive-btn" href="#"
                                               data-id="{{ $customer->id }}"
                                               data-name="{{ $customer->first_name }} {{ $customer->last_name }}">
                                                <i class="bi bi-archive me-2"></i>Archive
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-people fs-3 d-block mb-2"></i>
                                No active customers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($customers->hasPages())
                <div class="d-flex justify-content-center align-items-center gap-1 mt-3">
                    @if($customers->onFirstPage())
                        <button class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-chevron-left"></i></button>
                    @else
                        <a href="{{ $customers->previousPageUrl() }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-left"></i></a>
                    @endif
                    @for($i = 1; $i <= $customers->lastPage(); $i++)
                        @if($i == $customers->currentPage())
                            <span class="btn btn-sm btn-primary">{{ $i }}</span>
                        @else
                            <a href="{{ $customers->url($i) }}" class="btn btn-sm btn-outline-secondary">{{ $i }}</a>
                        @endif
                    @endfor
                    @if($customers->hasMorePages())
                        <a href="{{ $customers->nextPageUrl() }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-right"></i></a>
                    @else
                        <button class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-chevron-right"></i></button>
                    @endif
                </div>
                <p class="text-center text-muted small mt-2 mb-0">Page {{ $customers->currentPage() }} of {{ $customers->lastPage() }}</p>
            @endif
        </div>
    </div>


    {{-- ========================== --}}
    {{-- ARCHIVED CUSTOMERS TABLE  --}}
    {{-- ========================== --}}
    <div id="archivedView" style="display: none;">
        <div class="border rounded-3 p-4 mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-semibold">Archived Customers</span>
                <span class="text-muted small">{{ $archivedCustomers->total() }} total archived</span>
            </div>
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th><th>Name</th><th>Contact Number</th><th>Address</th><th>Status</th><th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($archivedCustomers as $customer)
                        <tr>
                            <td class="text-muted small">{{ $customer->id }}</td>
                            <td class="text-muted">{{ $customer->first_name }} {{ $customer->middle_name ? $customer->middle_name . ' ' : '' }}{{ $customer->last_name }}</td>
                            <td class="text-muted">{{ $customer->contact_number }}</td>
                            <td class="text-muted">{{ $customer->address }}</td>
                            <td><span class="badge bg-secondary">Archived</span></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <form action="{{ route('customers.archive', $customer->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                                onclick="return confirm('Restore {{ addslashes($customer->first_name . ' ' . $customer->last_name) }}?')">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>Restore
                                        </button>
                                    </form>
                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Permanently delete {{ addslashes($customer->first_name . ' ' . $customer->last_name) }}? This cannot be undone.')">
                                            <i class="bi bi-trash me-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-archive fs-3 d-block mb-2"></i>No archived customers.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($archivedCustomers->hasPages())
                <div class="d-flex justify-content-center align-items-center gap-1 mt-3">
                    @if($archivedCustomers->onFirstPage())
                        <button class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-chevron-left"></i></button>
                    @else
                        <a href="{{ $archivedCustomers->previousPageUrl() }}&tab=archived" class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-left"></i></a>
                    @endif
                    @for($i = 1; $i <= $archivedCustomers->lastPage(); $i++)
                        @if($i == $archivedCustomers->currentPage())
                            <span class="btn btn-sm btn-primary">{{ $i }}</span>
                        @else
                            <a href="{{ $archivedCustomers->url($i) }}&tab=archived" class="btn btn-sm btn-outline-secondary">{{ $i }}</a>
                        @endif
                    @endfor
                    @if($archivedCustomers->hasMorePages())
                        <a href="{{ $archivedCustomers->nextPageUrl() }}&tab=archived" class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-right"></i></a>
                    @else
                        <button class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-chevron-right"></i></button>
                    @endif
                </div>
                <p class="text-center text-muted small mt-2 mb-0">Page {{ $archivedCustomers->currentPage() }} of {{ $archivedCustomers->lastPage() }}</p>
            @endif
        </div>
    </div>
</div>


{{-- ============================================================ --}}
{{-- VIEW MODAL (Customer detail + credit history)               --}}
{{-- ============================================================ --}}
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- Customer info --}}
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width:30%;">Full Name</td>
                        <td id="view_name" class="fw-semibold"></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Contact Number</td>
                        <td id="view_contact" class="fw-semibold"></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Address</td>
                        <td id="view_address" class="fw-semibold"></td>
                    </tr>
                </table>

                <hr>

                {{-- Credit section header --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold mb-0">Credit History</h6>
                    <button class="btn btn-sm btn-primary" id="showCreditBtn" onclick="showCreditForm()">
                        <i class="bi bi-plus-lg me-1"></i> Add Credit
                    </button>
                </div>

                {{-- Add Credit Form --}}
                <div style="display:none;" id="addCreditForm">
                    <form action="{{ route('credits.store') }}" method="POST" class="border rounded p-3 mb-3 bg-light">
                        @csrf
                        <input type="hidden" name="customer_id" id="credit_customer_id">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" name="date" id="credit_date">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fuel Type</label>
                                <select class="form-select" name="fuel_type">
                                    <option value="Regular">Regular</option>
                                    <option value="Premium">Premium</option>
                                    <option value="Diesel">Diesel</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Price / Liter</label>
                                <input type="number" step="0.01" min="0" class="form-control"
                                       name="price" id="credit_price" placeholder="0.00">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Liters</label>
                                <input type="number" step="0.01" min="0" class="form-control"
                                       name="liters" id="credit_liters" placeholder="0.00">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Amount</label>
                                <input type="number" step="0.01" min="0" class="form-control bg-white"
                                       name="amount" id="credit_amount" placeholder="0.00" readonly>
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="hideCreditForm()">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm">Add Credit</button>
                        </div>
                    </form>
                </div>

                {{-- Loading spinner --}}
                <div id="creditLoadingSpinner" class="text-center py-3" style="display:none;">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    <span class="ms-2 text-muted small">Loading credits…</span>
                </div>

                {{-- Credit History Table --}}
                <table class="table table-sm table-hover" id="creditHistoryTable">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Fuel Type</th>
                            <th>Liters</th>
                            <th>Price / L</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Remaining</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="creditHistoryBody">
                        <tr>
                            <td colspan="9" class="text-center text-muted py-3">No credit records yet.</td>
                        </tr>
                    </tbody>
                </table>

                {{-- Running totals --}}
                <div class="d-flex justify-content-end gap-4 mt-1 small">
                    <span class="text-muted">Total Credits: <span class="fw-semibold text-dark" id="creditTotalAmount">₱0.00</span></span>
                    <span class="text-muted">Total Paid: <span class="fw-semibold text-success" id="creditTotalPaid">₱0.00</span></span>
                    <span class="text-muted">Outstanding: <span class="fw-semibold text-danger" id="creditTotalBalance">₱0.00</span></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


{{-- ============================================================ --}}
{{-- CREDIT DETAIL MODAL (payment history + add payment + status) --}}
{{-- ============================================================ --}}
<div class="modal fade" id="creditDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-receipt me-2"></i>Credit Detail
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                {{-- Credit summary card --}}
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="text-muted small mb-1">Date</div>
                            <div class="fw-semibold" id="det_date">—</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="text-muted small mb-1">Fuel Type</div>
                            <div class="fw-semibold" id="det_fuel">—</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="text-muted small mb-1">Liters × Price</div>
                            <div class="fw-semibold" id="det_liters_price">—</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="text-muted small mb-1">Total Amount</div>
                            <div class="fw-semibold text-primary" id="det_amount">—</div>
                        </div>
                    </div>
                </div>

                {{-- Balance progress bar --}}
                <div class="mb-4">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-success">Paid: <strong id="det_paid">₱0.00</strong></span>
                        <span class="text-danger">Remaining: <strong id="det_remaining">₱0.00</strong></span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" id="det_progress" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>

                {{-- Status badge + change status form --}}
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="text-muted small">Payment Status:</span>
                    <span id="det_status_badge"></span>
                    <button class="btn btn-sm btn-outline-secondary ms-auto" type="button"
                            data-bs-toggle="collapse" data-bs-target="#changeStatusForm">
                        <i class="bi bi-pencil me-1"></i>Change Status
                    </button>
                </div>

                {{-- Change status inline form --}}
                <div class="collapse mb-3" id="changeStatusForm">
                    <form method="POST" id="statusForm" class="border rounded p-3 bg-light d-flex align-items-end gap-3">
                        @csrf @method('PATCH')
                        <div class="flex-grow-1">
                            <label class="form-label small mb-1">New Status</label>
                            <select class="form-select form-select-sm" name="payment_status" id="statusSelect">
                                <option value="unpaid">Unpaid</option>
                                <option value="partial">Partial</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                    </form>
                </div>

                <hr>

                {{-- Add Payment Form --}}
                <div class="mb-3" id="addPaymentSection">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-semibold mb-0">Payment History</h6>
                        <button class="btn btn-sm btn-success" type="button"
                                data-bs-toggle="collapse" data-bs-target="#addPaymentForm"
                                id="addPaymentToggle">
                            <i class="bi bi-cash-coin me-1"></i>Add Payment
                        </button>
                    </div>

                    <div class="collapse mb-3" id="addPaymentForm">
                        <form method="POST" id="paymentForm" class="border rounded p-3 bg-light">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small">Payment Date</label>
                                    <input type="date" class="form-control form-control-sm"
                                           name="payment_date" id="pay_date">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">
                                        Amount Paid
                                        <span class="text-muted">(max: <span id="pay_max_label">₱0.00</span>)</span>
                                    </label>
                                    <input type="number" step="0.01" min="0.01"
                                           class="form-control form-control-sm"
                                           name="amount_paid" id="pay_amount" placeholder="0.00">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">Note <span class="text-muted">(optional)</span></label>
                                    <input type="text" class="form-control form-control-sm"
                                           name="note" id="pay_note" placeholder="e.g. downpayment, GCash">
                                </div>
                            </div>
                            <div class="mt-3 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-toggle="collapse" data-bs-target="#addPaymentForm">Cancel</button>
                                <button type="submit" class="btn btn-success btn-sm">Record Payment</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Payment history table --}}
                <div id="paymentLoadingSpinner" class="text-center py-2" style="display:none;">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                </div>

                <table class="table table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Amount Paid</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody id="paymentHistoryBody">
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">No payments yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


{{-- ===================== --}}
{{-- EDIT MODAL            --}}
{{-- ===================== --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_fname" name="fname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="edit_mname" name="mname">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_lname" name="lname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_contact" name="contact_number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_address" name="address" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary"
                        onclick="document.getElementById('editForm').submit()">Save Changes</button>
            </div>
        </div>
    </div>
</div>


{{-- ===================== --}}
{{-- ARCHIVE CONFIRM MODAL --}}
{{-- ===================== --}}
<div class="modal fade" id="archiveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Archive Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="bi bi-archive fs-1 text-warning d-block mb-2"></i>
                <p class="mb-1">Archive this customer?</p>
                <p class="fw-semibold mb-1" id="archiveName"></p>
                <p class="text-muted small mb-0">They will be hidden from the active table and moved to Archived.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="archiveForm" method="POST" style="display:inline;">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-archive me-1"></i>Archive
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- ===================== --}}
{{-- JAVASCRIPT            --}}
{{-- ===================== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Helpers ──────────────────────────────────────────────────────────────
    const fmt = n => '₱' + parseFloat(n || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    const statusBadge = s => ({
        unpaid:  '<span class="badge bg-danger">Unpaid</span>',
        partial: '<span class="badge bg-warning text-dark">Partial</span>',
        paid:    '<span class="badge bg-success">Paid</span>',
    }[s] || '<span class="badge bg-secondary">Unknown</span>');

    const fmtDate = d => new Date(d).toLocaleDateString('en-PH', {
        year: 'numeric', month: 'short', day: 'numeric'
    });

    // ── Tab: Active / Archived ────────────────────────────────────────────────
    const activeView   = document.getElementById('activeView');
    const archivedView = document.getElementById('archivedView');
    const archivedBtn  = document.getElementById('archivedTabBtn');
    const showFormBtn  = document.getElementById('showFormBtn');
    const customerForm = document.getElementById('customerForm');

    if (new URLSearchParams(window.location.search).get('tab') === 'archived') applyArchivedView();

    window.toggleView = tab => {
        if (tab === 'archived') { applyArchivedView(); history.replaceState(null, '', '?tab=archived'); }
        else                    { applyActiveView();   history.replaceState(null, '', window.location.pathname); }
    };

    function applyArchivedView() {
        activeView.style.display = 'none'; archivedView.style.display = 'block';
        customerForm.style.display = 'none'; showFormBtn.style.display = 'none';
        archivedBtn.innerHTML = '<i class="bi bi-people me-1"></i> Active Customers';
        archivedBtn.onclick = () => toggleView('active');
    }
    function applyActiveView() {
        activeView.style.display = 'block'; archivedView.style.display = 'none';
        showFormBtn.style.display = 'inline-block';
        archivedBtn.innerHTML = '<i class="bi bi-archive me-1"></i> Archived'
            + (@json($archivedCustomers->total()) > 0
                ? ' <span class="badge bg-secondary ms-1">' + @json($archivedCustomers->total()) + '</span>' : '');
        archivedBtn.onclick = () => toggleView('archived');
    }

    // ── Add Customer Form ────────────────────────────────────────────────────
    window.showForm = () => { customerForm.style.display = 'block'; showFormBtn.style.display = 'none'; customerForm.scrollIntoView({ behavior: 'smooth' }); };
    window.hideForm = () => { customerForm.style.display = 'none'; showFormBtn.style.display = 'inline-block'; };
    @if($errors->any()) showForm(); @endif

    // ── View Modal ───────────────────────────────────────────────────────────
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const mname = this.dataset.mname ? this.dataset.mname + ' ' : '';
            document.getElementById('view_name').textContent    = this.dataset.fname + ' ' + mname + this.dataset.lname;
            document.getElementById('view_contact').textContent = this.dataset.contact;
            document.getElementById('view_address').textContent = this.dataset.address;
            const cid = this.dataset.id;
            document.getElementById('credit_customer_id').value = cid;
            resetCreditForm();
            hideCreditForm();
            loadCreditHistory(cid);
            new bootstrap.Modal(document.getElementById('viewModal')).show();
        });
    });

    // ── Edit Modal ───────────────────────────────────────────────────────────
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('edit_fname').value   = this.dataset.fname;
            document.getElementById('edit_mname').value   = this.dataset.mname;
            document.getElementById('edit_lname').value   = this.dataset.lname;
            document.getElementById('edit_contact').value = this.dataset.contact;
            document.getElementById('edit_address').value = this.dataset.address;
            document.getElementById('editForm').action    = '/customers/' + this.dataset.id;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        });
    });

    // ── Archive Modal ────────────────────────────────────────────────────────
    document.querySelectorAll('.archive-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('archiveName').textContent = this.dataset.name;
            document.getElementById('archiveForm').action      = '/customers/' + this.dataset.id + '/archive';
            new bootstrap.Modal(document.getElementById('archiveModal')).show();
        });
    });

    // ── Credit Form show/hide ────────────────────────────────────────────────
    window.showCreditForm = () => {
        document.getElementById('addCreditForm').style.display = 'block';
        document.getElementById('showCreditBtn').style.display = 'none';
    };
    window.hideCreditForm = () => {
        document.getElementById('addCreditForm').style.display = 'none';
        document.getElementById('showCreditBtn').style.display = 'inline-block';
    };

    // ── Auto-calculate Amount = Price × Liters ───────────────────────────────
    const priceInput  = document.getElementById('credit_price');
    const litersInput = document.getElementById('credit_liters');
    const amountInput = document.getElementById('credit_amount');
    function calcAmount() {
        const p = parseFloat(priceInput.value) || 0;
        const l = parseFloat(litersInput.value) || 0;
        amountInput.value = (p * l).toFixed(2);
    }
    priceInput.addEventListener('input', calcAmount);
    litersInput.addEventListener('input', calcAmount);

    function resetCreditForm() {
        priceInput.value = ''; litersInput.value = ''; amountInput.value = '';
        document.getElementById('credit_date').value = new Date().toISOString().split('T')[0];
    }

    // ── Load Credit History ──────────────────────────────────────────────────
    function loadCreditHistory(customerId) {
        const tbody   = document.getElementById('creditHistoryBody');
        const spinner = document.getElementById('creditLoadingSpinner');

        spinner.style.display = 'block';
        tbody.innerHTML = '';

        fetch(`/customers/${customerId}/credits`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
        .then(credits => {
            spinner.style.display = 'none';

            if (!credits.length) {
                tbody.innerHTML = `<tr><td colspan="9" class="text-center text-muted py-3">No credit records yet.</td></tr>`;
                document.getElementById('creditTotalAmount').textContent  = '₱0.00';
                document.getElementById('creditTotalPaid').textContent    = '₱0.00';
                document.getElementById('creditTotalBalance').textContent = '₱0.00';
                return;
            }

            let totAmt = 0, totPaid = 0, totRemain = 0;

            tbody.innerHTML = credits.map(c => {
                totAmt    += parseFloat(c.amount)            || 0;
                totPaid   += parseFloat(c.amount_paid)       || 0;
                totRemain += parseFloat(c.remaining_balance) || 0;

                return `
                <tr style="cursor:pointer;" onclick="openCreditDetail(${c.id})" title="Click to view details">
                    <td>${fmtDate(c.date)}</td>
                    <td>${c.fuel_type}</td>
                    <td>${parseFloat(c.liters).toFixed(2)} L</td>
                    <td>${fmt(c.price)}</td>
                    <td>${fmt(c.amount)}</td>
                    <td class="text-success">${fmt(c.amount_paid)}</td>
                    <td class="${parseFloat(c.remaining_balance) > 0 ? 'text-danger fw-semibold' : 'text-success'}">${fmt(c.remaining_balance)}</td>
                    <td>${statusBadge(c.payment_status)}</td>
                    <td class="text-center">
                        <button class="btn btn-xs btn-outline-primary btn-sm py-0 px-2"
                                onclick="event.stopPropagation(); openCreditDetail(${c.id})">
                            <i class="bi bi-eye"></i>
                        </button>
                    </td>
                </tr>`;
            }).join('');

            document.getElementById('creditTotalAmount').textContent  = fmt(totAmt);
            document.getElementById('creditTotalPaid').textContent    = fmt(totPaid);
            document.getElementById('creditTotalBalance').textContent = fmt(totRemain);
        })
        .catch(err => {
            spinner.style.display = 'none';
            tbody.innerHTML = `<tr><td colspan="9" class="text-center text-danger py-3"><i class="bi bi-exclamation-triangle me-1"></i>Failed to load. Please try again.</td></tr>`;
        });
    }

    // ── Open Credit Detail Modal ─────────────────────────────────────────────
    window.openCreditDetail = function (creditId) {
        const spinner = document.getElementById('paymentLoadingSpinner');
        spinner.style.display = 'block';
        document.getElementById('paymentHistoryBody').innerHTML = '';

        fetch(`/credits/${creditId}/detail`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
        .then(c => {
            spinner.style.display = 'none';

            // Summary cards
            document.getElementById('det_date').textContent         = fmtDate(c.date);
            document.getElementById('det_fuel').textContent         = c.fuel_type;
            document.getElementById('det_liters_price').textContent = parseFloat(c.liters).toFixed(2) + 'L × ' + fmt(c.price);
            document.getElementById('det_amount').textContent       = fmt(c.amount);
            document.getElementById('det_paid').textContent         = fmt(c.amount_paid);
            document.getElementById('det_remaining').textContent    = fmt(c.remaining_balance);
            document.getElementById('det_status_badge').innerHTML   = statusBadge(c.payment_status);

            // Progress bar
            const pct = c.amount > 0 ? Math.min(100, (c.amount_paid / c.amount) * 100) : 0;
            document.getElementById('det_progress').style.width = pct.toFixed(1) + '%';

            // Status form
            document.getElementById('statusForm').action = `/credits/${c.id}/status`;
            document.getElementById('statusSelect').value = c.payment_status;

            // Payment form
            document.getElementById('paymentForm').action = `/credits/${c.id}/pay`;
            document.getElementById('pay_date').value = new Date().toISOString().split('T')[0];
            document.getElementById('pay_amount').max = c.remaining_balance;
            document.getElementById('pay_max_label').textContent = fmt(c.remaining_balance);

            // Hide add payment if fully paid
            document.getElementById('addPaymentSection').style.display =
                c.payment_status === 'paid' ? 'none' : 'block';

            // Payment history table
            const pbody = document.getElementById('paymentHistoryBody');
            if (!c.payments.length) {
                pbody.innerHTML = `<tr><td colspan="4" class="text-center text-muted py-3">No payments recorded yet.</td></tr>`;
            } else {
                pbody.innerHTML = c.payments.map((p, i) => `
                    <tr>
                        <td class="text-muted small">${i + 1}</td>
                        <td>${fmtDate(p.payment_date)}</td>
                        <td class="text-success fw-semibold">${fmt(p.amount_paid)}</td>
                        <td class="text-muted">${p.note ? p.note : '—'}</td>
                    </tr>`).join('');
            }

            // Open the modal (stack on top of viewModal)
            new bootstrap.Modal(document.getElementById('creditDetailModal')).show();
        })
        .catch(() => {
            spinner.style.display = 'none';
            alert('Failed to load credit details. Please try again.');
        });
    };

    // ── Search: clear input and submit ──────────────────────────────────────
    window.clearSearch = function () {
        document.getElementById('searchInput').value = '';
        document.getElementById('searchFilterForm').submit();
    };

    // ── Re-open modal after redirect (open_customer param) ──────────────────
    const urlParams    = new URLSearchParams(window.location.search);
    const openCustomer = urlParams.get('open_customer');
    if (openCustomer) {
        const viewBtn = document.querySelector(`.view-btn[data-id="${openCustomer}"]`);
        if (viewBtn) viewBtn.click();
        history.replaceState(null, '', window.location.pathname);
    }

});
</script>

@endsection