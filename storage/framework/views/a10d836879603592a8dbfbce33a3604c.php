<?php if(!$activeShift): ?>
    <div class="alert alert-warning">No active shift to close.</div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-4">Close Shift — <?php echo e($activeShift->sales_date->format('M d, Y')); ?></h4>

            <form method="POST" action="<?php echo e(route('shift.close')); ?>" id="close-shift-form">
                <?php echo csrf_field(); ?>
                
                <input type="hidden" name="shift_id" value="<?php echo e($activeShift->ShiftID); ?>">

                <!-- Opening Readings Summary -->
                <div class="mb-5">
                    <h6 class="fw-semibold mb-3">Opening Readings (from Shift Readings)</h6>
                    <div class="row g-3">
                        <?php $__currentLoopData = $activeShift->shiftReadings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reading): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4">
                                <div class="card bg-light border-0 p-3">
                                    <div class="small text-muted">
                                        <?php echo e($reading->pump->pump_name ?? 'Pump'); ?> —
                                        <?php echo e($reading->fuel->fuel_name ?? 'Fuel'); ?>

                                    </div>
                                    <strong><?php echo e(number_format($reading->opening_reading, 3)); ?> L</strong>
                                    
                                    <input type="hidden"
                                           name="opening_readings[<?php echo e($reading->ShiftReadingID); ?>]"
                                           value="<?php echo e($reading->opening_reading); ?>">
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Closing Readings -->
                
                <?php
                    $readingByPumpFuel = [];
                    foreach ($activeShift->shiftReadings as $sr) {
                        $pf = $sr->pump
                            ? optional(\App\Models\PumpFuel::where('PumpID', $sr->PumpID)
                                ->where('FuelID', $sr->FuelID)
                                ->first())
                            : null;
                        if ($pf) {
                            $readingByPumpFuel[$pf->PumpFuelID] = [
                                'opening' => (float) $sr->opening_reading,
                                'fuelId'  => $sr->FuelID,
                            ];
                        }
                    }
                ?>

                <div class="mb-5">
                    <h6 class="fw-semibold mb-3">Closing Totalizer Readings</h6>
                    <div class="row g-4">
                        <?php $__currentLoopData = $pumps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pump): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-light fw-semibold"><?php echo e($pump->pump_name); ?></div>
                                    <div class="card-body">
                                        <?php $__currentLoopData = $pump->pumpFuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $openingVal = $readingByPumpFuel[$pf->PumpFuelID]['opening'] ?? 0;
                                                $fuelId     = $readingByPumpFuel[$pf->PumpFuelID]['fuelId']  ?? $pf->FuelID;
                                            ?>
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    <?php echo e($pf->fuel->fuel_name ?? 'Fuel'); ?>

                                                </label>
                                                <input type="number"
                                                       step="0.001"
                                                       name="closing_readings[<?php echo e($pf->PumpFuelID); ?>]"
                                                       class="form-control closing-reading"
                                                       data-pf-id="<?php echo e($pf->PumpFuelID); ?>"
                                                       data-fuel-id="<?php echo e($fuelId); ?>"
                                                       data-opening="<?php echo e($openingVal); ?>"
                                                       data-label="<?php echo e($pump->pump_name); ?> — <?php echo e($pf->fuel->fuel_name ?? 'Fuel'); ?>"
                                                       value="<?php echo e(old('closing_readings.'.$pf->PumpFuelID)); ?>"
                                                       required>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Fuel Prices -->
                <div class="mb-5">
                    <h6 class="fw-semibold mb-3">Fuel Prices Today</h6>
                    <div class="row g-3">
                        <?php $__currentLoopData = $fuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fuel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4">
                                <label class="form-label"><?php echo e($fuel->fuel_name); ?> (₱/L)</label>
                                <input type="number"
                                       step="0.01"
                                       name="prices[<?php echo e($fuel->FuelID); ?>]"
                                       class="form-control price-input"
                                       data-fuel-id="<?php echo e($fuel->FuelID); ?>"
                                       value="<?php echo e(old('prices.'.$fuel->FuelID)); ?>"
                                       required>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

                <!-- Discounts & Credits -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h6>Discounts</h6>
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
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addDiscountRow()">
                            + Add Discount
                        </button>
                    </div>
                    <div class="col-lg-6">
                        <h6>Credits</h6>
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
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addCreditRow()">
                            + Add Credit
                        </button>
                    </div>
                </div>

                <hr class="my-5">

                <button type="submit" class="btn btn-danger btn-lg px-5">
                    <i class="bi bi-stop-circle me-2"></i> Close This Shift
                </button>
            </form>
        </div>
    </div>


<script>
(function () {
    // ── helpers ──────────────────────────────────────────────────────────
    const fmt  = (n) => new Intl.NumberFormat('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n);
    const fmt3 = (n) => new Intl.NumberFormat('en-PH', { minimumFractionDigits: 3, maximumFractionDigits: 3 }).format(n);

    // ── price lookup: fuelId → price ─────────────────────────────────────
    function getPrices() {
        const prices = {};
        document.querySelectorAll('.price-input').forEach(el => {
            const fuelId = el.dataset.fuelId;
            prices[fuelId] = parseFloat(el.value) || 0;
        });
        return prices;
    }

    // ── rebuild the live-calculation panel ───────────────────────────────
    function recalc() {
        const prices  = getPrices();
        const panel   = document.getElementById('live-calculation');
        const closers = document.querySelectorAll('.closing-reading');

        panel.innerHTML = '';
        let grandTotal = 0;

        closers.forEach(el => {
            const closing = parseFloat(el.value) || 0;
            const opening = parseFloat(el.dataset.opening) || 0;
            const fuelId  = el.dataset.fuelId;
            const label   = el.dataset.label || 'Unknown';
            const price   = prices[fuelId] || 0;
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

    // ── attach listeners ─────────────────────────────────────────────────
    document.querySelectorAll('.closing-reading, .price-input').forEach(el => {
        el.addEventListener('input', recalc);
    });

    // initial render (in case old() repopulates values)
    recalc();

    // ── discount rows ─────────────────────────────────────────────────────
    let discountIndex = 0;
    window.addDiscountRow = function () {
        const i    = discountIndex++;
        const body = document.getElementById('discounts-body');
        const tr   = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <select name="discounts[${i}][fuel_id]" class="form-select form-select-sm">
                    <option value="">—</option>
                    <?php $__currentLoopData = $fuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fuel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($fuel->FuelID); ?>"><?php echo e($fuel->fuel_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </td>
            <td>
                <select name="discounts[${i}][customer_id]" class="form-select form-select-sm">
                    <option value="">—</option>
                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c->CustomerID); ?>"><?php echo e($c->First_name); ?> <?php echo e($c->Last_name ?? ''); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </td>
            <td><input type="number" step="0.001" name="discounts[${i}][liters]"             class="form-control form-control-sm disc-liters-${i}"   value="0"></td>
            <td><input type="number" step="0.01"  name="discounts[${i}][retail_price]"       class="form-control form-control-sm disc-price-${i}"    value="0"></td>
            <td><input type="number" step="0.01"  name="discounts[${i}][discount_per_liter]" class="form-control form-control-sm disc-dpl-${i}"      value="0"></td>
            <td><input type="number" step="0.01"  name="discounts[${i}][discount_sale]"      class="form-control form-control-sm disc-total-${i}"    value="0" readonly></td>
            <td><input type="text"                name="discounts[${i}][description]"         class="form-control form-control-sm" placeholder="Reason"></td>
            <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">✕</button></td>`;
        body.appendChild(tr);

        // auto-compute discount_sale = liters × discount_per_liter
        const autoCalc = () => {
            const liters = parseFloat(tr.querySelector(`.disc-liters-${i}`).value) || 0;
            const dpl    = parseFloat(tr.querySelector(`.disc-dpl-${i}`).value)    || 0;
            tr.querySelector(`.disc-total-${i}`).value = (liters * dpl).toFixed(2);
        };
        tr.querySelector(`.disc-liters-${i}`).addEventListener('input', autoCalc);
        tr.querySelector(`.disc-dpl-${i}`).addEventListener('input', autoCalc);
    };

    // ── credit rows ───────────────────────────────────────────────────────
    // Smart 3-way calc: any 2 of (Liters, Price/L, Amount) filled → computes the third.
    // Selecting a Fuel auto-fills Price/L from the Fuel Prices section above.
    let creditIndex = 0;
    window.addCreditRow = function () {
        const i    = creditIndex++;
        const body = document.getElementById('credits-body');
        const tr   = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <select name="credits[${i}][fuel_id]" class="form-select form-select-sm cred-fuel-${i}">
                    <option value="">—</option>
                    <?php $__currentLoopData = $fuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fuel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($fuel->FuelID); ?>" data-fuel-id="<?php echo e($fuel->FuelID); ?>"><?php echo e($fuel->fuel_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </td>
            <td>
                <select name="credits[${i}][customer_id]" class="form-select form-select-sm">
                    <option value="">—</option>
                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c->CustomerID); ?>"><?php echo e($c->First_name); ?> <?php echo e($c->Last_name ?? ''); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

        const fuelSel   = tr.querySelector(`.cred-fuel-${i}`);
        const litersEl  = tr.querySelector(`.cred-liters-${i}`);
        const priceEl   = tr.querySelector(`.cred-price-${i}`);
        const retailEl  = tr.querySelector(`.cred-retail-${i}`);
        const chkEl     = tr.querySelector(`.cred-disc-chk-${i}`);
        const dsaleEl   = tr.querySelector(`.cred-dsale-${i}`);

        // When fuel selected → auto-fill price from the Fuel Prices section
        fuelSel.addEventListener('change', function () {
            const fuelId = this.options[this.selectedIndex]?.dataset.fuelId;
            if (fuelId) {
                const priceInput = document.querySelector(`.price-input[data-fuel-id="${fuelId}"]`);
                if (priceInput && priceInput.value) {
                    priceEl.value = parseFloat(priceInput.value).toFixed(2);
                    smartCalc('price'); // recalc after auto-fill
                }
            }
        });

        // 3-way smart calc:
        // - liters changed  → if price known, compute amount; else if amount known, compute price
        // - price changed   → if liters known, compute amount; else if amount known, compute liters
        // - amount changed  → if liters known, compute price; else if price known, compute liters
        function smartCalc(changed) {
            const L = parseFloat(litersEl.value);
            const P = parseFloat(priceEl.value);
            const A = parseFloat(retailEl.value);
            const hasL = !isNaN(L) && litersEl.value !== '';
            const hasP = !isNaN(P) && priceEl.value  !== '';
            const hasA = !isNaN(A) && retailEl.value  !== '';

            if (changed === 'liters') {
                if (hasL && hasP)       retailEl.value  = (L * P).toFixed(2);
                else if (hasL && hasA)  priceEl.value   = L > 0 ? (A / L).toFixed(2) : '';
            } else if (changed === 'price') {
                if (hasP && hasL)       retailEl.value  = (L * P).toFixed(2);
                else if (hasP && hasA)  litersEl.value  = P > 0 ? (A / P).toFixed(3) : '';
            } else if (changed === 'amount') {
                if (hasA && hasL && L > 0) priceEl.value  = (A / L).toFixed(2);
                else if (hasA && hasP && P > 0) litersEl.value = (A / P).toFixed(3);
            }

            // also recalc discounted_sale if checkbox is on (keeps it in sync)
            if (chkEl.checked && dsaleEl.value !== '') {
                // discounted_sale is manually entered; just leave it unless empty
                if (dsaleEl.value === '' || parseFloat(dsaleEl.value) === 0) {
                    dsaleEl.value = retailEl.value;
                }
            }
        }

        litersEl.addEventListener('input', () => smartCalc('liters'));
        priceEl.addEventListener('input',  () => smartCalc('price'));
        retailEl.addEventListener('input', () => smartCalc('amount'));

        // toggle discounted_sale enabled/disabled
        chkEl.addEventListener('change', function () {
            dsaleEl.disabled = !this.checked;
            if (!this.checked) {
                dsaleEl.value = '';
            } else {
                // pre-fill with retail amount as starting point
                if (retailEl.value) dsaleEl.value = retailEl.value;
            }
        });
    };
})();
</script>
<?php endif; ?><?php /**PATH C:\Users\john mark bolanon\laravel\Seal-gasolineStation\resources\views/Shift/partials/close.blade.php ENDPATH**/ ?>