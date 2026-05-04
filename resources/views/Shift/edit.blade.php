@extends('layouts.app')

@section('title', 'Edit Shift')
@section('subtitle', 'Edit shift record for ' . $shift->sales_date->format('M d, Y'))

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('shift.management', ['view' => 'home']) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
    </a>
    <h4 class="mb-0 fw-bold">Edit Shift — {{ $shift->sales_date->format('M d, Y') }}</h4>
    <span class="badge bg-{{ $shift->status === 'open' ? 'success' : 'secondary' }} ms-2">
        {{ ucfirst($shift->status) }}
    </span>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form method="POST" action="{{ route('shift.update', $shift->ShiftID) }}" id="edit-shift-form">
    @csrf
    @method('PUT')

    {{-- ── SHIFT READINGS ── --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light fw-semibold">
            <i class="bi bi-speedometer2 me-2"></i>Shift Readings
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($shift->shiftReadings as $reading)
                    @php
                        $pf = \App\Models\PumpFuel::where('PumpID', $reading->PumpID)
                                ->where('FuelID', $reading->FuelID)->first();
                    @endphp
                    <div class="col-md-4">
                        <div class="card border-0 bg-light p-3">
                            <div class="small text-muted fw-semibold mb-2">
                                {{ $reading->pump->pump_name ?? 'Pump' }} — {{ $reading->fuel->fuel_name ?? 'Fuel' }}
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label small mb-1">Opening</label>
                                    <input type="number" step="0.001"
                                           name="readings[{{ $reading->ShiftReadingID }}][opening_reading]"
                                           class="form-control form-control-sm"
                                           value="{{ old('readings.'.$reading->ShiftReadingID.'.opening_reading', $reading->opening_reading) }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-1">Closing</label>
                                    <input type="number" step="0.001"
                                           name="readings[{{ $reading->ShiftReadingID }}][closing_reading]"
                                           class="form-control form-control-sm"
                                           value="{{ old('readings.'.$reading->ShiftReadingID.'.closing_reading', $reading->closing_reading) }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small mb-1">Price/L (₱)</label>
                                    <input type="number" step="0.0001"
                                           name="readings[{{ $reading->ShiftReadingID }}][price_per_liter]"
                                           class="form-control form-control-sm"
                                           value="{{ old('readings.'.$reading->ShiftReadingID.'.price_per_liter', $reading->price_per_liter ?? $pf?->price_per_liter) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── EXISTING DISCOUNTS ── --}}
    @php
        $existingDiscounts = collect();
        foreach($shift->sales as $sale) {
            $existingDiscounts = $existingDiscounts->merge($sale->salesDiscounts ?? []);
        }
    @endphp
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light fw-semibold">
            <i class="bi bi-tag me-2"></i>Discounts
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm align-middle" id="discount-table">
                    <thead class="table-light">
                        <tr>
                            <th>Fuel</th>
                            <th>Customer</th>
                            <th>Liters</th>
                            <th>Price/L</th>
                            <th>Disc/L</th>
                            <th>Total Disc</th>
                            <th>Reason</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="discounts-body">
                        {{-- Existing rows --}}
                        @foreach($existingDiscounts as $sd)
                            <tr data-existing-id="{{ $sd->SalesDiscountID }}">
                                <td>
                                    <select name="existing_discounts[{{ $sd->SalesDiscountID }}][fuel_id]" class="form-select form-select-sm">
                                        @foreach($fuels as $f)
                                            <option value="{{ $f->FuelID }}" @selected($f->FuelID == $sd->FuelID)>{{ $f->fuel_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="existing_discounts[{{ $sd->SalesDiscountID }}][customer_id]" class="form-select form-select-sm">
                                        <option value="">— None —</option>
                                        @foreach($customers as $c)
                                            <option value="{{ $c->CustomerID }}" @selected($c->CustomerID == $sd->CustomerID)>{{ $c->First_name }} {{ $c->Last_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" step="0.001" name="existing_discounts[{{ $sd->SalesDiscountID }}][liters]" class="form-control form-control-sm ed-liters-{{ $sd->SalesDiscountID }}" value="{{ $sd->liters }}"></td>
                                <td><input type="number" step="0.01"  name="existing_discounts[{{ $sd->SalesDiscountID }}][retail_price]" class="form-control form-control-sm" value="{{ $sd->retail_price }}"></td>
                                <td><input type="number" step="0.01"  name="existing_discounts[{{ $sd->SalesDiscountID }}][discount_per_liter]" class="form-control form-control-sm ed-dpl-{{ $sd->SalesDiscountID }}" value="{{ $sd->discount_per_liter }}"></td>
                                <td><input type="number" step="0.01"  name="existing_discounts[{{ $sd->SalesDiscountID }}][discount_sale]" class="form-control form-control-sm ed-total-{{ $sd->SalesDiscountID }}" value="{{ $sd->discount_sale }}" readonly></td>
                                <td><input type="text" name="existing_discounts[{{ $sd->SalesDiscountID }}][description]" class="form-control form-control-sm" value="{{ $sd->description }}" placeholder="Reason"></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="deleteExistingDiscount({{ $sd->SalesDiscountID }}, this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <input type="hidden" name="existing_discounts[{{ $sd->SalesDiscountID }}][_delete]" value="0" class="delete-flag-d-{{ $sd->SalesDiscountID }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addDiscountRow()">
                <i class="bi bi-plus me-1"></i>Add Discount
            </button>
        </div>
    </div>

    {{-- ── EXISTING CREDITS ── --}}
    @php
        $existingCredits = collect();
        foreach($shift->sales as $sale) {
            $existingCredits = $existingCredits->merge($sale->salesCredits ?? []);
        }
    @endphp
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light fw-semibold">
            <i class="bi bi-credit-card me-2"></i>Credits
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm align-middle" id="credit-table">
                    <thead class="table-light">
                        <tr>
                            <th>Fuel</th>
                            <th>Customer</th>
                            <th>Liters</th>
                            <th>Price/L</th>
                            <th>Amount</th>
                            <th>Discounted?</th>
                            <th>Disc. Amount</th>
                            <th>Reason</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="credits-body">
                        {{-- Existing rows --}}
                        @foreach($existingCredits as $sc)
                            <tr data-existing-id="{{ $sc->SalesCreditID }}">
                                <td>
                                    <select name="existing_credits[{{ $sc->SalesCreditID }}][fuel_id]" class="form-select form-select-sm">
                                        @foreach($fuels as $f)
                                            <option value="{{ $f->FuelID }}" @selected($f->FuelID == ($sc->credit->FuelID ?? null))>{{ $f->fuel_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="existing_credits[{{ $sc->SalesCreditID }}][customer_id]" class="form-select form-select-sm">
                                        <option value="">— None —</option>
                                        @foreach($customers as $c)
                                            <option value="{{ $c->CustomerID }}" @selected($c->CustomerID == $sc->CustomerID)>{{ $c->First_name }} {{ $c->Last_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" step="0.001" name="existing_credits[{{ $sc->SalesCreditID }}][liters]"        class="form-control form-control-sm ec-liters-{{ $sc->SalesCreditID }}"  value="{{ $sc->liters }}"></td>
                                <td><input type="number" step="0.01"  name="existing_credits[{{ $sc->SalesCreditID }}][retail_price]"  class="form-control form-control-sm ec-price-{{ $sc->SalesCreditID }}"   value="{{ $sc->retail_price }}"></td>
                                <td><input type="number" step="0.01"  name="existing_credits[{{ $sc->SalesCreditID }}][retail_sale]"   class="form-control form-control-sm ec-retail-{{ $sc->SalesCreditID }}"  value="{{ $sc->retail_sale }}"></td>
                                <td class="text-center">
                                    <input type="checkbox" name="existing_credits[{{ $sc->SalesCreditID }}][discounted]" class="form-check-input ec-chk-{{ $sc->SalesCreditID }}" value="1" @checked($sc->discounted)>
                                </td>
                                <td><input type="number" step="0.01"  name="existing_credits[{{ $sc->SalesCreditID }}][discounted_sale]" class="form-control form-control-sm ec-dsale-{{ $sc->SalesCreditID }}" value="{{ $sc->discounted_sale }}" @disabled(!$sc->discounted)></td>
                                <td><input type="text" name="existing_credits[{{ $sc->SalesCreditID }}][description]" class="form-control form-control-sm" value="{{ $sc->description }}" placeholder="Reason"></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="deleteExistingCredit({{ $sc->SalesCreditID }}, this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <input type="hidden" name="existing_credits[{{ $sc->SalesCreditID }}][_delete]" value="0" class="delete-flag-c-{{ $sc->SalesCreditID }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addCreditRow()">
                <i class="bi bi-plus me-1"></i>Add Credit
            </button>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-5">
            <i class="bi bi-save me-2"></i>Save Changes
        </button>
        <a href="{{ route('shift.management', ['view' => 'home']) }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<script>
window.fuelOptions     = {!! json_encode($fuels->map(fn($f) => ['fuel_id' => $f->FuelID, 'fuel_name' => $f->fuel_name])) !!};
window.customerOptions = {!! json_encode($customers->map(fn($c) => ['customer_id' => $c->CustomerID, 'customer_name' => $c->First_name . ' ' . ($c->Last_name ?? '')])) !!};

(function () {

    // ── Build option HTML ────────────────────────────────────────────────
    function buildFuelOptions(selectedId = null) {
        let html = '<option value="">— Select Fuel —</option>';
        window.fuelOptions.forEach(f => {
            const sel = f.fuel_id == selectedId ? 'selected' : '';
            html += `<option value="${f.fuel_id}" data-fuel-id="${f.fuel_id}" ${sel}>${f.fuel_name}</option>`;
        });
        return html;
    }

    function buildCustomerOptions(selectedId = null) {
        let html = '<option value="">— Select Customer —</option>';
        window.customerOptions.forEach(c => {
            const sel = c.customer_id == selectedId ? 'selected' : '';
            html += `<option value="${c.customer_id}" ${sel}>${c.customer_name}</option>`;
        });
        return html;
    }

    // ── Attach listeners to existing discount rows ───────────────────────
    document.querySelectorAll('#discounts-body tr[data-existing-id]').forEach(tr => {
        const id      = tr.dataset.existingId;
        const litersEl = tr.querySelector(`[class*="ed-liters-"]`);
        const dplEl    = tr.querySelector(`[class*="ed-dpl-"]`);
        const totalEl  = tr.querySelector(`[class*="ed-total-"]`);
        if (litersEl && dplEl && totalEl) {
            const calc = () => {
                const l = parseFloat(litersEl.value) || 0;
                const d = parseFloat(dplEl.value)    || 0;
                totalEl.value = (l * d).toFixed(2);
            };
            litersEl.addEventListener('input', calc);
            dplEl.addEventListener('input', calc);
        }
    });

    // ── Attach listeners to existing credit rows ─────────────────────────
    document.querySelectorAll('#credits-body tr[data-existing-id]').forEach(tr => {
        const id       = tr.dataset.existingId;
        const litersEl = tr.querySelector(`[class*="ec-liters-"]`);
        const priceEl  = tr.querySelector(`[class*="ec-price-"]`);
        const retailEl = tr.querySelector(`[class*="ec-retail-"]`);
        const chkEl    = tr.querySelector(`[class*="ec-chk-"]`);
        const dsaleEl  = tr.querySelector(`[class*="ec-dsale-"]`);

        if (litersEl && priceEl && retailEl) {
            const calc = () => {
                const l = parseFloat(litersEl.value) || 0;
                const p = parseFloat(priceEl.value)  || 0;
                retailEl.value = (l * p).toFixed(2);
                if (chkEl?.checked && dsaleEl) dsaleEl.value = retailEl.value;
            };
            litersEl.addEventListener('input', calc);
            priceEl.addEventListener('input', calc);
        }
        if (chkEl && dsaleEl) {
            chkEl.addEventListener('change', function () {
                dsaleEl.disabled = !this.checked;
                if (!this.checked) dsaleEl.value = '';
                else if (retailEl) dsaleEl.value = retailEl.value;
            });
        }
    });

    // ── Delete existing rows (soft-flag) ─────────────────────────────────
    window.deleteExistingDiscount = function (id, btn) {
        if (!confirm('Remove this discount?')) return;
        document.querySelector(`.delete-flag-d-${id}`).value = '1';
        btn.closest('tr').style.opacity  = '0.4';
        btn.closest('tr').style.pointerEvents = 'none';
        btn.textContent = 'Removed';
    };

    window.deleteExistingCredit = function (id, btn) {
        if (!confirm('Remove this credit?')) return;
        document.querySelector(`.delete-flag-c-${id}`).value = '1';
        btn.closest('tr').style.opacity  = '0.4';
        btn.closest('tr').style.pointerEvents = 'none';
        btn.textContent = 'Removed';
    };

    // ── New Discount rows ────────────────────────────────────────────────
    let discountIndex = 0;
    window.addDiscountRow = function () {
        const i    = discountIndex++;
        const body = document.getElementById('discounts-body');
        const tr   = document.createElement('tr');
        tr.innerHTML = `
            <td><select name="new_discounts[${i}][fuel_id]" class="form-select form-select-sm nd-fuel-${i}">${buildFuelOptions()}</select></td>
            <td><select name="new_discounts[${i}][customer_id]" class="form-select form-select-sm">${buildCustomerOptions()}</select></td>
            <td><input type="number" step="0.001" name="new_discounts[${i}][liters]"             class="form-control form-control-sm nd-liters-${i}" value="0"></td>
            <td><input type="number" step="0.01"  name="new_discounts[${i}][retail_price]"       class="form-control form-control-sm nd-price-${i}"  value="0"></td>
            <td><input type="number" step="0.01"  name="new_discounts[${i}][discount_per_liter]" class="form-control form-control-sm nd-dpl-${i}"    value="0"></td>
            <td><input type="number" step="0.01"  name="new_discounts[${i}][discount_sale]"      class="form-control form-control-sm nd-total-${i}"  value="0" readonly></td>
            <td><input type="text"                name="new_discounts[${i}][description]"         class="form-control form-control-sm" placeholder="Reason"></td>
            <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()"><i class="bi bi-trash"></i></button></td>`;
        body.appendChild(tr);

        const litersEl = tr.querySelector(`.nd-liters-${i}`);
        const dplEl    = tr.querySelector(`.nd-dpl-${i}`);
        const totalEl  = tr.querySelector(`.nd-total-${i}`);
        const calc = () => totalEl.value = ((parseFloat(litersEl.value)||0) * (parseFloat(dplEl.value)||0)).toFixed(2);
        litersEl.addEventListener('input', calc);
        dplEl.addEventListener('input', calc);
    };

    // ── New Credit rows ──────────────────────────────────────────────────
    let creditIndex = 0;
    window.addCreditRow = function () {
        const i    = creditIndex++;
        const body = document.getElementById('credits-body');
        const tr   = document.createElement('tr');
        tr.innerHTML = `
            <td><select name="new_credits[${i}][fuel_id]" class="form-select form-select-sm nc-fuel-${i}">${buildFuelOptions()}</select></td>
            <td><select name="new_credits[${i}][customer_id]" class="form-select form-select-sm">${buildCustomerOptions()}</select></td>
            <td><input type="number" step="0.001" name="new_credits[${i}][liters]"         class="form-control form-control-sm nc-liters-${i}" value=""></td>
            <td><input type="number" step="0.01"  name="new_credits[${i}][retail_price]"   class="form-control form-control-sm nc-price-${i}"  value=""></td>
            <td><input type="number" step="0.01"  name="new_credits[${i}][retail_sale]"    class="form-control form-control-sm nc-retail-${i}" value=""></td>
            <td class="text-center"><input type="checkbox" name="new_credits[${i}][discounted]" class="form-check-input nc-chk-${i}" value="1"></td>
            <td><input type="number" step="0.01"  name="new_credits[${i}][discounted_sale]" class="form-control form-control-sm nc-dsale-${i}" value="" disabled></td>
            <td><input type="text"                name="new_credits[${i}][description]"      class="form-control form-control-sm" placeholder="Reason"></td>
            <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()"><i class="bi bi-trash"></i></button></td>`;
        body.appendChild(tr);

        const litersEl = tr.querySelector(`.nc-liters-${i}`);
        const priceEl  = tr.querySelector(`.nc-price-${i}`);
        const retailEl = tr.querySelector(`.nc-retail-${i}`);
        const chkEl    = tr.querySelector(`.nc-chk-${i}`);
        const dsaleEl  = tr.querySelector(`.nc-dsale-${i}`);

        const calc = () => {
            const l = parseFloat(litersEl.value) || 0;
            const p = parseFloat(priceEl.value)  || 0;
            retailEl.value = (l * p).toFixed(2);
            if (chkEl.checked) dsaleEl.value = retailEl.value;
        };
        litersEl.addEventListener('input', calc);
        priceEl.addEventListener('input', calc);

        chkEl.addEventListener('change', function () {
            dsaleEl.disabled = !this.checked;
            if (!this.checked) dsaleEl.value = '';
            else dsaleEl.value = retailEl.value;
        });

        // Auto-select first fuel
        const fuelSel = tr.querySelector(`.nc-fuel-${i}`);
        if (fuelSel.options.length > 1) fuelSel.selectedIndex = 1;
    };

})();
</script>
@endsection