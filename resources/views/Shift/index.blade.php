@extends('layouts.app')

@section('title', 'Shift Management')
@section('subtitle', 'Manage daily shift operations')

@push('styles')
<style>
    :root {
        --fuel-premium: #f59e0b;
        --fuel-diesel:  #ef4444;
        --fuel-regular: #10b981;
    }

    .shift-nav .nav-link {
        font-weight: 600;
        padding: 0.85rem 1.5rem;
        border-bottom: 3px solid transparent;
        color: #64748b;
    }
    .shift-nav .nav-link.active {
        color: #dc2626;
        border-bottom-color: #dc2626;
    }

    .pump-card {
        transition: all 0.25s ease;
    }
    .pump-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 25px -5px rgb(0 0 0 / 0.1);
    }

    .fuel-badge {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.35em 0.75em;
        border-radius: 9999px;
    }

    .stat-value {
        font-size: 1.85rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
        color: #64748b;
    }
</style>
@endpush

@section('content')

<!-- Navigation Tabs -->
<ul class="nav nav-tabs shift-nav mb-4">
    <li class="nav-item">
        <a class="nav-link {{ $view === 'home' ? 'active' : '' }}" 
           href="{{ route('shift.management', ['view' => 'home']) }}">
            <i class="bi bi-house-door me-1"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $view === 'open' ? 'active' : '' }}" 
           href="{{ route('shift.management', ['view' => 'open']) }}">
            <i class="bi bi-play-circle me-1"></i> Open Shift
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $view === 'close' ? 'active' : '' }}" 
           href="{{ route('shift.management', ['view' => 'close']) }}">
            <i class="bi bi-stop-circle me-1"></i> Close Shift
        </a>
    </li>
</ul>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- HOME VIEW --}}
@if($view === 'home')
    @include('Shift.partials.home')
@endif

{{-- OPEN SHIFT VIEW --}}
@if($view === 'open')
    @include('Shift.partials.open')
@endif

{{-- CLOSE SHIFT VIEW --}}
@if($view === 'close')
    @include('Shift.partials.close')
@endif

@endsection

@section('scripts')
<script>
// ==================== GLOBAL DATA ====================
const customerOptions = `@foreach($customers as $c)<option value="{{ $c->id }}">{{ $c->first_name }} {{ $c->last_name }}</option>@endforeach`;
const fuelOptions     = `@foreach($fuels as $f)<option value="{{ $f->id }}">{{ $f->fuel_name }}</option>@endforeach`;

let discountIndex = 0;
let creditIndex   = 0;

// ==================== DISCOUNT ROWS ====================
function addDiscountRow() {
    const tbody = document.getElementById('discounts-body');
    const i = discountIndex++;
    const row = document.createElement('tr');
    row.innerHTML = `
        <td><select name="discounts[${i}][fuel_id]" class="form-select form-select-sm">${fuelOptions}</select></td>
        <td><select name="discounts[${i}][customer_id]" class="form-select form-select-sm">${customerOptions}</select></td>
        <td><input type="number" step="0.001" name="discounts[${i}][liters]" class="form-control form-control-sm discount-liters" placeholder="0.000"></td>
        <td><input type="number" step="0.01" name="discounts[${i}][retail_price]" class="form-control form-control-sm discount-retail" placeholder="0.00"></td>
        <td><input type="number" step="0.01" name="discounts[${i}][discount_per_liter]" class="form-control form-control-sm discount-per-liter" placeholder="0.00"></td>
        <td><input type="number" step="0.01" name="discounts[${i}][discount_sale]" class="form-control form-control-sm discount-sale" readonly></td>
        <td><input type="text" name="discounts[${i}][description]" class="form-control form-control-sm" placeholder="Reason"></td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">×</button></td>
    `;
    tbody.appendChild(row);
    attachDiscountListeners(row);
}

function attachDiscountListeners(row) {
    const liters = row.querySelector('.discount-liters');
    const retail = row.querySelector('.discount-retail');
    const perLiter = row.querySelector('.discount-per-liter');
    const discSale = row.querySelector('.discount-sale');

    function calculate() {
        const l = parseFloat(liters.value) || 0;
        const r = parseFloat(retail.value) || 0;
        const d = parseFloat(perLiter.value) || 0;
        discSale.value = (l * d).toFixed(2);
    }

    [liters, retail, perLiter].forEach(input => {
        input.addEventListener('input', calculate);
    });
}

// ==================== CREDIT ROWS ====================
function addCreditRow() {
    const tbody = document.getElementById('credits-body');
    const i = creditIndex++;
    const row = document.createElement('tr');
    row.innerHTML = `
        <td><select name="credits[${i}][customer_id]" class="form-select form-select-sm">${customerOptions}</select></td>
        <td><input type="number" step="0.001" name="credits[${i}][liters]" class="form-control form-control-sm credit-liters" placeholder="0.000"></td>
        <td><input type="number" step="0.01" name="credits[${i}][retail_price]" class="form-control form-control-sm credit-retail" placeholder="0.00"></td>
        <td><input type="number" step="0.01" name="credits[${i}][retail_sale]" class="form-control form-control-sm credit-retail-sale" readonly></td>
        <td class="text-center"><input type="checkbox" name="credits[${i}][discounted]" class="form-check-input credit-discounted"></td>
        <td><input type="number" step="0.01" name="credits[${i}][discounted_sale]" class="form-control form-control-sm credit-disc-sale" readonly></td>
        <td><input type="text" name="credits[${i}][description]" class="form-control form-control-sm" placeholder="Reason"></td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">×</button></td>
    `;
    tbody.appendChild(row);
    attachCreditListeners(row);
}

function attachCreditListeners(row) {
    const liters = row.querySelector('.credit-liters');
    const retail = row.querySelector('.credit-retail');
    const retailSale = row.querySelector('.credit-retail-sale');
    const discSale = row.querySelector('.credit-disc-sale');
    const checkbox = row.querySelector('.credit-discounted');

    function calculate() {
        const l = parseFloat(liters.value) || 0;
        const r = parseFloat(retail.value) || 0;
        const isDiscounted = checkbox.checked;

        retailSale.value = (l * r).toFixed(2);
        discSale.value = isDiscounted ? (l * r * 0.9).toFixed(2) : '0.00'; // Adjust discount logic as needed
    }

    [liters, retail, checkbox].forEach(el => el.addEventListener('input', calculate));
}

// ==================== LIVE GROSS CALCULATION ====================
function calculateGrossSales() {
    let totalGross = 0;
    const breakdownHTML = [];

    document.querySelectorAll('.closing-reading').forEach(input => {
        const pfId = input.dataset.pfId;
        const closing = parseFloat(input.value) || 0;

        // Find corresponding opening reading from hidden or data attribute
        const openingInput = document.querySelector(`input[name="opening_readings[${pfId}]"]`) || 
                            document.querySelector(`[data-opening-pf="${pfId}"]`);

        const opening = openingInput ? parseFloat(openingInput.value) || 0 : 0;
        const liters = Math.max(0, closing - opening);

        // Get price for this fuel (you may need to adjust selector based on your price inputs)
        const fuelId = input.closest('.card-body')?.querySelector('select')?.value || 
                      document.querySelector(`.price-input[data-fuel-id="${pfId}"]`)?.value;

        const priceInput = document.querySelector(`input[name="prices[${fuelId}]"]`) || 
                          document.querySelector('.price-input');
        const price = parseFloat(priceInput?.value) || 0;

        const value = liters * price;
        totalGross += value;

        if (liters > 0) {
            breakdownHTML.push(`
                <div class="col-md-4">
                    <div class="card border-0 bg-light p-3">
                        <small class="text-muted">PumpFuel ${pfId}</small>
                        <div class="fw-bold">${liters.toFixed(3)} L × ₱${price.toFixed(2)}</div>
                        <div class="text-primary fw-semibold">₱${value.toFixed(2)}</div>
                    </div>
                </div>
            `);
        }
    });

    document.getElementById('gross-total').textContent = totalGross.toFixed(2);
    document.getElementById('live-calculation').innerHTML = breakdownHTML.join('');
}

// ==================== FORM SUBMISSION CLEANUP ====================
document.getElementById('close-shift-form')?.addEventListener('submit', function() {
    // Remove empty discount/credit rows
    document.querySelectorAll('#discounts-body tr, #credits-body tr').forEach(row => {
        const litersInput = row.querySelector('input[name*="liters"]');
        if (!litersInput || parseFloat(litersInput.value) <= 0) {
            row.remove();
        }
    });
});

// Attach live calculation to all relevant inputs
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('closing-reading') || 
        e.target.classList.contains('price-input')) {
        calculateGrossSales();
    }
});

// ==================== ACTION HELPERS ====================
function archiveShift(id) {
    if (confirm('Archive this shift?')) fetchAction(`/shift/${id}/archive`, 'PATCH');
}
function restoreShift(id) {
    if (confirm('Restore this shift?')) fetchAction(`/shift/${id}/restore`, 'PATCH');
}
function deleteShift(id) {
    if (confirm('Permanently delete this shift?')) fetchAction(`/shift/${id}`, 'DELETE');
}
function cancelOpenShift(id) {
    if (confirm('Cancel this open shift?')) fetchAction(`/shift/${id}`, 'DELETE');
}

function fetchAction(url, method) {
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    }).then(response => {
        if (response.ok) location.reload();
        else alert('Action failed. Please try again.');
    });
}

// Initialize live calculation when page loads
document.addEventListener('DOMContentLoaded', function() {
    calculateGrossSales();
});
</script>
@endsection