@if(!$activeShift)
    <div class="alert alert-warning">No active shift to close.</div>
@else
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-4">Close Shift — {{ $activeShift->sales_date->format('M d, Y') }}</h4>

            <form method="POST" action="{{ route('shift.close') }}" id="close-shift-form">
                @csrf
                <input type="hidden" name="shift_id" value="{{ $activeShift->ShiftID }}">

                <!-- Opening Readings Summary -->
                <div class="mb-5">
                    <h6 class="fw-semibold mb-3">Opening Readings (from Shift Readings)</h6>
                    <div class="row g-3">
                        @foreach($activeShift->shiftReadings as $reading)
                            <div class="col-md-4">
                                <div class="card bg-light border-0 p-3">
                                    <div class="small text-muted">
                                        {{ $reading->pump->pump_name ?? 'Pump' }} —
                                        {{ $reading->fuel->fuel_name ?? 'Fuel' }}
                                    </div>
                                    <strong>{{ number_format($reading->opening_reading, 3) }} L</strong>
                                    <input type="hidden"
                                           name="opening_readings[{{ $reading->ShiftReadingID }}]"
                                           value="{{ $reading->opening_reading }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @php
                    $readingByPumpFuel = [];
                    foreach ($activeShift->shiftReadings as $sr) {
                        $pf = \App\Models\PumpFuel::where('PumpID', $sr->PumpID)
                                ->where('FuelID', $sr->FuelID)
                                ->first();
                        if ($pf) {
                            $readingByPumpFuel[$pf->PumpFuelID] = [
                                'opening'         => (float) $sr->opening_reading,
                                'fuelId'          => $sr->FuelID,
                                'price_per_liter' => (float) $pf->price_per_liter,
                            ];
                        }
                    }
                @endphp

                <!-- Closing Readings -->
                <div class="mb-5">
                    <h6 class="fw-semibold mb-3">Closing Totalizer Readings</h6>
                    <div class="row g-4">
                        @foreach($pumps as $pump)
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-light fw-semibold">{{ $pump->pump_name }}</div>
                                    <div class="card-body">
                                        @foreach($pump->pumpFuels as $pf)
                                            @php
                                                $meta     = $readingByPumpFuel[$pf->PumpFuelID] ?? null;
                                                $openingVal = $meta['opening'] ?? 0;
                                                $fuelId     = $meta['fuelId']  ?? $pf->FuelID;
                                                $storedPrice = $meta['price_per_liter'] ?? $pf->price_per_liter ?? 0;
                                            @endphp
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    {{ $pf->fuel->fuel_name ?? 'Fuel' }}
                                                </label>
                                                <input type="number"
                                                       step="0.001"
                                                       name="closing_readings[{{ $pf->PumpFuelID }}]"
                                                       class="form-control closing-reading"
                                                       data-pf-id="{{ $pf->PumpFuelID }}"
                                                       data-fuel-id="{{ $fuelId }}"
                                                       data-opening="{{ $openingVal }}"
                                                       data-label="{{ $pump->pump_name }} — {{ $pf->fuel->fuel_name ?? 'Fuel' }}"
                                                       value="{{ old('closing_readings.'.$pf->PumpFuelID) }}"
                                                       required>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Fuel Prices -->
                <div class="mb-5">
                    <h6 class="fw-semibold mb-3">
                        Fuel Prices Today
                        <span class="text-muted fw-normal small ms-2">(auto-filled · edit to override)</span>
                    </h6>
                    <div class="row g-3">
                        @foreach($pumps as $pump)
                            @foreach($pump->pumpFuels as $pf)
                                @php
                                    $meta        = $readingByPumpFuel[$pf->PumpFuelID] ?? null;
                                    $storedPrice = $meta['price_per_liter'] ?? $pf->price_per_liter ?? 0;
                                    $fuelId      = $meta['fuelId'] ?? $pf->FuelID;
                                @endphp
                                <div class="col-md-4">
                                    <label class="form-label">
                                        {{ $pump->pump_name }} — {{ $pf->fuel->fuel_name ?? 'Fuel' }} (₱/L)
                                    </label>
                                    <input type="number"
                                           step="0.0001"
                                           name="prices[{{ $pf->PumpFuelID }}]"
                                           class="form-control price-input"
                                           data-pf-id="{{ $pf->PumpFuelID }}"
                                           data-fuel-id="{{ $fuelId }}"
                                           value="{{ old('prices.'.$pf->PumpFuelID, $storedPrice) }}">
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <!-- Live Calculation -->
                <div class="mb-5">
                    <h6 class="fw-semibold mb-3">Live Gross Sales Calculation</h6>
                    <div id="live-calculation" class="row g-3"></div>
                    <div class="mt-3">
                        <strong>Gross Sales: ₱<span id="gross-total" class="fs-4 text-primary">0.00</span></strong>
                    </div>
                </div>

                <!-- Discounts -->
                <div class="mb-4">
                    <h5 class="fw-semibold mb-3">Discounts</h5>
                    <div class="table-responsive">
                        <table class="table table-sm" id="discount-table">
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
                            <tbody id="discounts-body"></tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addDiscountRow()">
                        + Add Discount
                    </button>
                </div>

                <!-- Credits -->
                <div class="mb-5">
                    <h5 class="fw-semibold mb-3">Credits</h5>
                    <div class="table-responsive">
                        <table class="table table-sm" id="credit-table">
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
                            <tbody id="credits-body"></tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addCreditRow()">
                        + Add Credit
                    </button>
                </div>

                <hr class="my-5">

                <button type="submit" class="btn btn-danger btn-lg px-5">
                    <i class="bi bi-stop-circle me-2"></i> Close This Shift
                </button>
            </form>
        </div>
    </div>

<script>
// ── Pass PHP data to JavaScript ──────────────────────────────────────────
window.fuelOptions     = {!! json_encode($fuels->map(fn($f) => ['fuel_id' => $f->FuelID, 'fuel_name' => $f->fuel_name])) !!};
window.customerOptions = {!! json_encode($customers->map(fn($c) => ['customer_id' => $c->CustomerID, 'customer_name' => $c->First_name . ' ' . ($c->Last_name ?? '')])) !!};

(function () {
    const fmt  = (n) => new Intl.NumberFormat('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n);
    const fmt3 = (n) => new Intl.NumberFormat('en-PH', { minimumFractionDigits: 3, maximumFractionDigits: 3 }).format(n);

    // ── Helper: build <option> tags from data array ──────────────────────
    function buildFuelOptions(placeholder = '— Select Fuel —') {
        let html = `<option value="">${placeholder}</option>`;
        window.fuelOptions.forEach(f => {
            html += `<option value="${f.fuel_id}" data-fuel-id="${f.fuel_id}">${f.fuel_name}</option>`;
        });
        return html;
    }

    function buildCustomerOptions(placeholder = '— Select Customer —') {
        let html = `<option value="">${placeholder}</option>`;
        window.customerOptions.forEach(c => {
            html += `<option value="${c.customer_id}">${c.customer_name}</option>`;
        });
        return html;
    }

    // ── price lookups ────────────────────────────────────────────────────
    function getPrices() {
        const prices = {};
        document.querySelectorAll('.price-input').forEach(el => {
            prices[el.dataset.pfId] = parseFloat(el.value) || 0;
        });
        return prices;
    }

    function getPricesByFuelId() {
        const prices = {};
        document.querySelectorAll('.price-input').forEach(el => {
            const fuelId = el.dataset.fuelId;
            if (fuelId && !prices[fuelId]) {
                prices[fuelId] = parseFloat(el.value) || 0;
            }
        });
        return prices;
    }

    function getPriceByFuelId(fuelId) {
        const prices = getPricesByFuelId();
        return prices[fuelId] || 0;
    }

    // ── live calculation ─────────────────────────────────────────────────
    function recalc() {
        const prices  = getPrices();
        const panel   = document.getElementById('live-calculation');
        const closers = document.querySelectorAll('.closing-reading');

        panel.innerHTML = '';
        let grandTotal = 0;

        closers.forEach(el => {
            const closing = parseFloat(el.value) || 0;
            const opening = parseFloat(el.dataset.opening) || 0;
            const pfId    = el.dataset.pfId;
            const label   = el.dataset.label || 'Unknown';
            const price   = prices[pfId] || 0;
            const liters  = Math.max(0, closing - opening);
            const gross   = liters * price;
            grandTotal   += gross;

            const col = document.createElement('div');
            col.className = 'col-md-4';
            col.innerHTML = `
                <div class="card border-0 bg-light p-3">
                    <div class="small text-muted mb-1">${label}</div>
                    <div class="small">Liters: <strong>${fmt3(liters)}</strong></div>
                    <div class="small">Price: ₱${fmt(price)}/L</div>
                    <div class="mt-1 fw-semibold text-success">₱${fmt(gross)}</div>
                </div>`;
            panel.appendChild(col);
        });

        document.getElementById('gross-total').textContent = fmt(grandTotal);
    }

    document.querySelectorAll('.closing-reading, .price-input').forEach(el => {
        el.addEventListener('input', () => {
            recalc();
            refreshRowPrices();
        });
    });

    recalc();

    // ── refresh row prices when fuel prices change ───────────────────────
    function refreshRowPrices() {
        document.querySelectorAll('#discounts-body tr, #credits-body tr').forEach(tr => {
            const fuelSel = tr.querySelector('select[name*="[fuel_id]"]');
            const priceEl = tr.querySelector('input[name*="[retail_price]"]');
            if (fuelSel && priceEl && fuelSel.value) {
                const option = fuelSel.options[fuelSel.selectedIndex];
                const fuelId = option?.dataset?.fuelId;
                if (fuelId) {
                    const newPrice = getPriceByFuelId(fuelId);
                    if (newPrice > 0) {
                        priceEl.value = newPrice.toFixed(2);
                        priceEl.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                }
            }
        });
    }

    // ── discount rows ────────────────────────────────────────────────────
    let discountIndex = 0;
    window.addDiscountRow = function () {
        const i    = discountIndex++;
        const body = document.getElementById('discounts-body');
        const tr   = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <select name="discounts[${i}][fuel_id]" class="form-select form-select-sm disc-fuel-${i}">
                    ${buildFuelOptions()}
                </select>
            </td>
            <td>
                <select name="discounts[${i}][customer_id]" class="form-select form-select-sm">
                    ${buildCustomerOptions()}
                </select>
            </td>
            <td><input type="number" step="0.001" name="discounts[${i}][liters]"             class="form-control form-control-sm disc-liters-${i}"   value="0"></td>
            <td><input type="number" step="0.01"  name="discounts[${i}][retail_price]"       class="form-control form-control-sm disc-price-${i}"    value="0"></td>
            <td><input type="number" step="0.01"  name="discounts[${i}][discount_per_liter]" class="form-control form-control-sm disc-dpl-${i}"      value="0"></td>
            <td><input type="number" step="0.01"  name="discounts[${i}][discount_sale]"      class="form-control form-control-sm disc-total-${i}"    value="0" readonly></td>
            <td><input type="text"                name="discounts[${i}][description]"         class="form-control form-control-sm" placeholder="Reason"></td>
            <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">✕</button></td>`;
        body.appendChild(tr);

        const fuelSel  = tr.querySelector(`.disc-fuel-${i}`);
        const litersEl = tr.querySelector(`.disc-liters-${i}`);
        const priceEl  = tr.querySelector(`.disc-price-${i}`);
        const dplEl    = tr.querySelector(`.disc-dpl-${i}`);
        const totalEl  = tr.querySelector(`.disc-total-${i}`);

        fuelSel.addEventListener('change', function () {
            const option = this.options[this.selectedIndex];
            const fuelId = option?.dataset?.fuelId;
            if (fuelId) {
                const price = getPriceByFuelId(fuelId);
                if (price > 0) {
                    priceEl.value = price.toFixed(2);
                    autoCalc();
                }
            }
        });

        const autoCalc = () => {
            const liters = parseFloat(litersEl.value) || 0;
            const dpl    = parseFloat(dplEl.value)    || 0;
            totalEl.value = (liters * dpl).toFixed(2);
        };
        litersEl.addEventListener('input', autoCalc);
        dplEl.addEventListener('input', autoCalc);
        priceEl.addEventListener('input', autoCalc);

        // Auto-select first real fuel and fill price immediately
        if (fuelSel.options.length > 1) {
            fuelSel.selectedIndex = 1;
            fuelSel.dispatchEvent(new Event('change'));
        }
    };

    // ── credit rows ──────────────────────────────────────────────────────
    let creditIndex = 0;
    window.addCreditRow = function () {
        const i    = creditIndex++;
        const body = document.getElementById('credits-body');
        const tr   = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <select name="credits[${i}][fuel_id]" class="form-select form-select-sm cred-fuel-${i}">
                    ${buildFuelOptions()}
                </select>
            </td>
            <td>
                <select name="credits[${i}][customer_id]" class="form-select form-select-sm">
                    ${buildCustomerOptions()}
                </select>
            </td>
            <td><input type="number" step="0.001" name="credits[${i}][liters]"       class="form-control form-control-sm cred-liters-${i}" value=""></td>
            <td><input type="number" step="0.01"  name="credits[${i}][retail_price]" class="form-control form-control-sm cred-price-${i}"  value=""></td>
            <td><input type="number" step="0.01"  name="credits[${i}][retail_sale]"  class="form-control form-control-sm cred-retail-${i}" value=""></td>
            <td class="text-center">
                <input type="checkbox" name="credits[${i}][discounted]" class="form-check-input cred-disc-chk-${i}" value="1">
            </td>
            <td><input type="number" step="0.01"  name="credits[${i}][discounted_sale]" class="form-control form-control-sm cred-dsale-${i}" value="" disabled></td>
            <td><input type="text"                name="credits[${i}][description]"      class="form-control form-control-sm" placeholder="Reason"></td>
            <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">✕</button></td>`;
        body.appendChild(tr);

        const fuelSel  = tr.querySelector(`.cred-fuel-${i}`);
        const litersEl = tr.querySelector(`.cred-liters-${i}`);
        const priceEl  = tr.querySelector(`.cred-price-${i}`);
        const retailEl = tr.querySelector(`.cred-retail-${i}`);
        const chkEl    = tr.querySelector(`.cred-disc-chk-${i}`);
        const dsaleEl  = tr.querySelector(`.cred-dsale-${i}`);

        fuelSel.addEventListener('change', function () {
            const option = this.options[this.selectedIndex];
            const fuelId = option?.dataset?.fuelId;
            if (fuelId) {
                const price = getPriceByFuelId(fuelId);
                if (price > 0) {
                    priceEl.value = price.toFixed(2);
                    smartCalc('price');
                }
            }
        });

        function smartCalc(changed) {
            const L = parseFloat(litersEl.value);
            const P = parseFloat(priceEl.value);
            const A = parseFloat(retailEl.value);
            const hasL = !isNaN(L) && litersEl.value !== '';
            const hasP = !isNaN(P) && priceEl.value  !== '';
            const hasA = !isNaN(A) && retailEl.value  !== '';

            if (changed === 'liters') {
                if (hasL && hasP)      retailEl.value = (L * P).toFixed(2);
                else if (hasL && hasA) priceEl.value  = L > 0 ? (A / L).toFixed(2) : '';
            } else if (changed === 'price') {
                if (hasP && hasL)      retailEl.value  = (L * P).toFixed(2);
                else if (hasP && hasA) litersEl.value  = P > 0 ? (A / P).toFixed(3) : '';
            } else if (changed === 'amount') {
                if (hasA && hasL && L > 0)      priceEl.value  = (A / L).toFixed(2);
                else if (hasA && hasP && P > 0) litersEl.value = (A / P).toFixed(3);
            }

            if (chkEl.checked) {
                if (!dsaleEl.value || parseFloat(dsaleEl.value) === 0) {
                    dsaleEl.value = retailEl.value;
                }
            }
        }

        litersEl.addEventListener('input', () => smartCalc('liters'));
        priceEl.addEventListener('input',  () => smartCalc('price'));
        retailEl.addEventListener('input', () => smartCalc('amount'));

        chkEl.addEventListener('change', function () {
            dsaleEl.disabled = !this.checked;
            if (!this.checked) {
                dsaleEl.value = '';
            } else {
                if (retailEl.value) dsaleEl.value = retailEl.value;
            }
        });

        // Auto-select first real fuel and fill price immediately
        if (fuelSel.options.length > 1) {
            fuelSel.selectedIndex = 1;
            fuelSel.dispatchEvent(new Event('change'));
        }
    };
})();
</script>
@endif