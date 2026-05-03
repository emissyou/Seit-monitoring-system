@extends('layouts.app')

@section('title', 'Discounts')

@push('styles')
<style>
    .table th {
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748b;
    }
    .discount-active { background: #d1fae5; color: #065f46; }
    .discount-expired { background: #fee2e2; color: #991b1b; }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Discount Management</h4>
        <p class="text-muted mb-0">Create and manage customer discounts with time limits</p>
    </div>
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addDiscountModal">
        <i class="bi bi-plus-circle me-2"></i>New Discount
    </button>
</div>

<!-- Add Discount Modal -->
<div class="modal fade" id="addDiscountModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Discount</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="discountForm" method="POST" action="{{ route('discounts.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer <span class="text-danger">*</span></label>
                            <select name="customer_id" class="form-select" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->first_name }} {{ $customer->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Discount Type</label>
                            <select name="discount_type" class="form-select" required>
                                <option value="per_liter">Per Liter</option>
                                <option value="fixed_amount">Fixed Amount</option>
                                <option value="percentage">Percentage (%)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="discount_value" 
                                   class="form-control" placeholder="e.g. 2.00 or 10" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" 
                                   value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2" 
                                      placeholder="e.g. Promotional discount for loyal customer"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('discountForm').submit()">
                    Create Discount
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Discounts Table -->
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Date Range</th>
                    <th>Customer</th>
                    <th>Discount Type</th>
                    <th>Value</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($discounts as $discount)
                    <tr>
                        <td>
                            {{ $discount->start_date->format('M d') }} - {{ $discount->end_date->format('M d, Y') }}
                        </td>
                        <td>{{ $discount->customer->first_name ?? '' }} {{ $discount->customer->last_name ?? '' }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $discount->discount_type)) }}</td>
                        <td>
                            @if($discount->discount_type == 'percentage')
                                {{ $discount->discount_value }}%
                            @else
                                ₱{{ number_format($discount->discount_value, 2) }}
                            @endif
                        </td>
                        <td>{{ $discount->description ?? '—' }}</td>
                        <td>
                            @if($discount->isActive())
                                <span class="badge discount-active">Active</span>
                            @else
                                <span class="badge discount-expired">Expired</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-warning" 
                                    onclick="archiveDiscount({{ $discount->id }})">
                                <i class="bi bi-archive"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted">No discounts created yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
function archiveDiscount(id) {
    if (confirm('Archive this discount?')) {
        fetch(`/discounts/${id}/archive`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(r => {
            if (r.ok) location.reload();
            else alert('Failed to archive');
        });
    }
}
</script>
@endsection