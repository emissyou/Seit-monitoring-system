@extends('layouts.app')

@section('title', 'Credit Logs')

@push('styles')
<style>
    .table th { font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.6px; color: #64748b; font-weight: 600; }
    .badge-unpaid  { background: #fee2e2; color: #991b1b; }
    .badge-partial { background: #fef3c7; color: #92400e; }
    .badge-paid    { background: #d1fae5; color: #065f46; }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Credit Logs</h4>
        <p class="text-muted mb-0">Manage customer credits</p>
    </div>
    <a href="{{ route('credits.index') }}" class="btn btn-outline-secondary">All Credits</a>
</div>

<!-- Credits Table -->
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Fuel Type</th>
                        <th class="text-end">Liters</th>
                        <th class="text-end">Amount</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Balance</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($credits as $credit)
                        <tr>
                            <td>{{ $credit->date?->format('M d, Y') }}</td>
                            <td>{{ $credit->customer->first_name ?? '' }} {{ $credit->customer->last_name ?? '' }}</td>
                            <td>{{ $credit->fuel_type }}</td>
                            <td class="text-end">{{ number_format($credit->liters, 3) }}</td>
                            <td class="text-end fw-semibold">₱{{ number_format($credit->amount, 2) }}</td>
                            <td class="text-end text-success">₱{{ number_format($credit->amount_paid, 2) }}</td>
                            <td class="text-end fw-bold {{ $credit->remaining_balance > 0 ? 'text-danger' : 'text-success' }}">
                                ₱{{ number_format($credit->remaining_balance, 2) }}
                            </td>
                            <td>
                                <span class="badge 
                                    @if($credit->payment_status == 'paid') badge-paid 
                                    @elseif($credit->payment_status == 'partial') badge-partial 
                                    @else badge-unpaid @endif">
                                    {{ ucfirst($credit->payment_status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="viewCreditDetail({{ $credit->id }})">
                                                <i class="bi bi-eye me-2"></i> View Detail
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-warning" href="#" 
                                               onclick="archiveCredit({{ $credit->id }})">
                                                <i class="bi bi-archive me-2"></i> Archive
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center py-5 text-muted">No credit records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $credits->links() }}
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function viewCreditDetail(id) {
    fetch(`/credits/${id}/detail`)
        .then(r => r.json())
        .then(data => {
            let html = `Credit Detail\n\nDate: ${data.date}\nFuel: ${data.fuel_type}\nAmount: ₱${parseFloat(data.amount).toFixed(2)}\nPaid: ₱${parseFloat(data.amount_paid).toFixed(2)}\nBalance: ₱${parseFloat(data.remaining_balance).toFixed(2)}`;
            alert(html);
        });
}

function archiveCredit(id) {
    if (confirm('Archive this credit record?')) {
        fetch(`/credits/${id}/archive`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Failed to archive credit');
            }
        });
    }
}
</script>
@endsection