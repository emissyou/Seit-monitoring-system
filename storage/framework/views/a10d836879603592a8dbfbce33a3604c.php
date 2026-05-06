
<style>
    .close-shift-wrap * { box-sizing: border-box; font-family: 'Geist', 'DM Sans', system-ui, sans-serif; }

    .close-shift-wrap .shift-subpage-header { margin-bottom: 24px; }
    .close-shift-wrap .breadcrumb-label {
        font-size: 11px; font-weight: 600; letter-spacing: .08em;
        text-transform: uppercase; color: #9ca3af; margin-bottom: 4px;
    }
    .close-shift-wrap h1 { font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 2px; }
    .close-shift-wrap .page-sub { font-size: 13px; color: #6b7280; margin: 0; }

    .alert-card-warn {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 16px 20px; border-radius: 12px; font-size: 13px;
        background: #fef3c7; border: 1px solid #fde68a; color: #92400e;
        margin-bottom: 20px;
    }
    .alert-card-warn i { color: #d97706; font-size: 16px; margin-top: 1px; }

    /* Section card */
    .cs-section {
        background: #fff; border: 1px solid #e5e7eb;
        border-radius: 14px; margin-bottom: 16px; overflow: hidden;
    }
    .cs-section-head {
        padding: 16px 22px;
        border-bottom: 1px solid #f3f4f6;
        display: flex; align-items: center; gap: 10px;
    }
    .cs-section-head .section-icon {
        width: 32px; height: 32px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; flex-shrink: 0;
    }
    .cs-section-head .section-title { font-size: 14px; font-weight: 700; color: #111827; }
    .cs-section-head .section-sub { font-size: 12px; color: #9ca3af; }
    .cs-section-body { padding: 20px 22px; }

    /* Opening readings grid */
    .opening-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px;
    }
    .opening-pill {
        background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px 14px;
    }
    .opening-pill .op-label { font-size: 11px; color: #9ca3af; margin-bottom: 3px; }
    .opening-pill .op-value { font-size: 15px; font-weight: 700; color: #111827; }

    /* Pump grid for closing */
    .pump-close-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    @media (max-width: 640px) { .pump-close-grid { grid-template-columns: 1fr; } }

    .pump-close-card { border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
    .pump-close-head {
        padding: 11px 15px; background: #f9fafb; border-bottom: 1px solid #e5e7eb;
        display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: #111827;
    }
    .pump-close-head i { color: #6b7280; }
    .pump-close-body { padding: 14px; }

    /* Prices grid */
    .price-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; }

    /* Fuel input */
    .f-input-group { margin-bottom: 12px; }
    .f-input-group:last-child { margin-bottom: 0; }
    .f-input-group label {
        display: flex; align-items: center; gap: 6px;
        font-size: 11px; font-weight: 600; color: #6b7280;
        text-transform: uppercase; letter-spacing: .04em; margin-bottom: 5px;
    }
    .f-dot { width: 7px; height: 7px; border-radius: 50%; }
    .f-input {
        width: 100%; padding: 8px 12px; font-size: 13px;
        border: 1px solid #e5e7eb; border-radius: 8px;
        background: #f9fafb; color: #111827; outline: none;
        transition: border-color .15s, box-shadow .15s;
    }
    .f-input:focus {
        border-color: #ef4444; background: #fff;
        box-shadow: 0 0 0 3px rgba(239,68,68,.1);
    }
    .f-input-hint { font-size: 11px; color: #9ca3af; margin-top: 3px; }

    /* Live calc */
    .live-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px; }
    .live-card {
        border: 1px solid #e5e7eb; border-radius: 10px; padding: 14px;
        background: #f9fafb;
    }
    .live-card .lc-label { font-size: 11px; color: #9ca3af; margin-bottom: 6px; }
    .live-card .lc-liters { font-size: 12px; color: #374151; margin-bottom: 2px; }
    .live-card .lc-price { font-size: 12px; color: #374151; margin-bottom: 6px; }
    .live-card .lc-gross { font-size: 16px; font-weight: 800; color: #10b981; }

    .gross-total-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 18px; background: #f0fdf4;
        border: 1px solid #d1fae5; border-radius: 10px; margin-top: 12px;
    }
    .gross-total-row .gt-label { font-size: 13px; color: #065f46; font-weight: 600; }
    .gross-total-row .gt-value { font-size: 22px; font-weight: 800; color: #10b981; }

    /* Tables for discounts / credits */
    .section-table { width: 100%; border-collapse: collapse; }
    .section-table thead tr { border-bottom: 1px solid #f3f4f6; }
    .section-table thead th {
        padding: 9px 10px; font-size: 10px; font-weight: 700;
        letter-spacing: .07em; text-transform: uppercase; color: #9ca3af;
        text-align: left; white-space: nowrap;
    }
    .section-table tbody tr { border-bottom: 1px solid #f9fafb; }
    .section-table tbody td { padding: 8px 10px; }
    .section-table .ts-input {
        width: 100%; padding: 6px 9px; font-size: 12px;
        border: 1px solid #e5e7eb; border-radius: 7px; background: #f9fafb; outline: none;
    }
    .section-table .ts-input:focus { border-color: #ef4444; background: #fff; }
    .section-table .ts-select {
        width: 100%; padding: 6px 9px; font-size: 12px;
        border: 1px solid #e5e7eb; border-radius: 7px; background: #f9fafb; outline: none;
    }

    .btn-add-row {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 16px; border-radius: 7px; font-size: 12px; font-weight: 600;
        border: 1px dashed #e5e7eb; background: transparent;
        color: #6b7280; cursor: pointer; margin-top: 10px;
        transition: all .15s;
    }
    .btn-add-row:hover { border-color: #ef4444; color: #ef4444; background: #fff5f5; }

    .btn-remove-row {
        width: 26px; height: 26px; border-radius: 6px; border: 1px solid #fecaca;
        background: #fff5f5; color: #ef4444; cursor: pointer; font-size: 13px;
        display: inline-flex; align-items: center; justify-content: center;
        transition: all .15s;
    }
    .btn-remove-row:hover { background: #fee2e2; }

    .divider { border: none; border-top: 1px solid #f3f4f6; margin: 20px 0; }

    /* Final submit */
    .submit-row {
        display: flex; align-items: center; gap: 16px;
        padding: 22px 0 4px;
    }
    .btn-close-shift {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 12px 32px; border-radius: 9px;
        background: #ef4444; color: #fff; font-size: 14px; font-weight: 700;
        border: none; cursor: pointer; transition: background .15s;
    }
    .btn-close-shift:hover { background: #dc2626; }
    .btn-cancel-link { font-size: 13px; color: #9ca3af; text-decoration: none; transition: color .15s; }
    .btn-cancel-link:hover { color: #374151; }
</style>

<div class="close-shift-wrap">
    <div class="shift-subpage-header">
        <div class="breadcrumb-label">Operations • Shift</div>
        <h1>Close Shift</h1>
        <p class="page-sub">Enter closing readings, adjust prices, and record discounts & credits.</p>
    </div>

    <?php if(!$activeShift): ?>
        <div class="alert-card-warn">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>No active shift to close. <a href="<?php echo e(route('shift.management', ['view' => 'open'])); ?>" style="color:#92400e; font-weight:600;">Open a shift</a> first.</div>
        </div>
    <?php else: ?>

    <?php
        $fuelDots = ['Premium' => '#f59e0b', 'Regular' => '#10b981', 'Diesel' => '#ef4444'];
        $readingByPumpFuel = [];
        foreach ($activeShift->shiftReadings as $sr) {
            $pf = \App\Models\PumpFuel::where('PumpID', $sr->PumpID)->where('FuelID', $sr->FuelID)->first();
            if ($pf) {
                $readingByPumpFuel[$pf->PumpFuelID] = [
                    'opening'         => (float) $sr->opening_reading,
                    'fuelId'          => $sr->FuelID,
                    'price_per_liter' => (float) $pf->price_per_liter,
                ];
            }
        }
    ?>

    <form method="POST" action="<?php echo e(route('shift.close')); ?>" id="close-shift-form">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="shift_id" value="<?php echo e($activeShift->ShiftID); ?>">

        
        <div class="cs-section">
            <div class="cs-section-head">
                <div class="section-icon" style="background:#eff6ff; color:#3b82f6;"><i class="bi bi-bookmark-check"></i></div>
                <div>
                    <div class="section-title">Opening Readings</div>
                    <div class="section-sub"><?php echo e($activeShift->sales_date->format('M d, Y')); ?> · recorded at shift open</div>
                </div>
            </div>
            <div class="cs-section-body">
                <div class="opening-grid">
                    <?php $__currentLoopData = $activeShift->shiftReadings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reading): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="opening-pill">
                            <div class="op-label"><?php echo e($reading->pump->pump_name ?? 'Pump'); ?> — <?php echo e($reading->fuel->fuel_name ?? 'Fuel'); ?></div>
                            <div class="op-value"><?php echo e(number_format($reading->opening_reading, 3)); ?> L</div>
                            <input type="hidden" name="opening_readings[<?php echo e($reading->ShiftReadingID); ?>]" value="<?php echo e($reading->opening_reading); ?>">
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        
        <div class="cs-section">
            <div class="cs-section-head">
                <div class="section-icon" style="background:#fee2e2; color:#ef4444;"><i class="bi bi-speedometer2"></i></div>
                <div>
                    <div class="section-title">Closing Totalizer Readings</div>
                    <div class="section-sub">Enter the meter value at the end of this shift.</div>
                </div>
            </div>
            <div class="cs-section-body">
                <div class="pump-close-grid">
                    <?php $__currentLoopData = $pumps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pump): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="pump-close-card">
                            <div class="pump-close-head">
                                <i class="bi bi-fuel-pump"></i> <?php echo e($pump->pump_name); ?>

                            </div>
                            <div class="pump-close-body">
                                <?php $__currentLoopData = $pump->pumpFuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $meta        = $readingByPumpFuel[$pf->PumpFuelID] ?? null;
                                        $openingVal  = $meta['opening'] ?? 0;
                                        $fuelId      = $meta['fuelId'] ?? $pf->FuelID;
                                        $storedPrice = $meta['price_per_liter'] ?? $pf->price_per_liter ?? 0;
                                        $fuelName    = $pf->fuel->fuel_name ?? 'Fuel';
                                        $dotColor    = $fuelDots[$fuelName] ?? '#6366f1';
                                    ?>
                                    <div class="f-input-group">
                                        <label>
                                            <span class="f-dot" style="background:<?php echo e($dotColor); ?>;"></span>
                                            <?php echo e($fuelName); ?>

                                        </label>
                                        <input type="number"
                                               step="0.001"
                                               name="closing_readings[<?php echo e($pf->PumpFuelID); ?>]"
                                               class="f-input closing-reading"
                                               data-pf-id="<?php echo e($pf->PumpFuelID); ?>"
                                               data-fuel-id="<?php echo e($fuelId); ?>"
                                               data-opening="<?php echo e($openingVal); ?>"
                                               data-label="<?php echo e($pump->pump_name); ?> — <?php echo e($fuelName); ?>"
                                               value="<?php echo e(old('closing_readings.'.$pf->PumpFuelID)); ?>"
                                               placeholder="Enter closing reading (L)"
                                               required>
                                        <div class="f-input-hint">Opening: <?php echo e(number_format($openingVal, 3)); ?> L</div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        
        <div class="cs-section">
            <div class="cs-section-head">
                <div class="section-icon" style="background:#fef3c7; color:#d97706;"><i class="bi bi-tag"></i></div>
                <div>
                    <div class="section-title">Fuel Prices</div>
                    <div class="section-sub">Auto-filled from last recorded prices · edit to override.</div>
                </div>
            </div>
            <div class="cs-section-body">
                <div class="price-grid">
                    <?php $__currentLoopData = $pumps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pump): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $pump->pumpFuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $meta        = $readingByPumpFuel[$pf->PumpFuelID] ?? null;
                                $storedPrice = $meta['price_per_liter'] ?? $pf->price_per_liter ?? 0;
                                $fuelId      = $meta['fuelId'] ?? $pf->FuelID;
                                $fuelName    = $pf->fuel->fuel_name ?? 'Fuel';
                                $dotColor    = $fuelDots[$fuelName] ?? '#6366f1';
                            ?>
                            <div class="f-input-group">
                                <label>
                                    <span class="f-dot" style="background:<?php echo e($dotColor); ?>;"></span>
                                    <?php echo e($pump->pump_name); ?> — <?php echo e($fuelName); ?> (₱/L)
                                </label>
                                <input type="number"
                                       step="0.0001"
                                       name="prices[<?php echo e($pf->PumpFuelID); ?>]"
                                       class="f-input price-input"
                                       data-pf-id="<?php echo e($pf->PumpFuelID); ?>"
                                       data-fuel-id="<?php echo e($fuelId); ?>"
                                       value="<?php echo e(old('prices.'.$pf->PumpFuelID, $storedPrice)); ?>">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        
        <div class="cs-section">
            <div class="cs-section-head">
                <div class="section-icon" style="background:#d1fae5; color:#10b981;"><i class="bi bi-calculator"></i></div>
                <div>
                    <div class="section-title">Live Gross Sales</div>
                    <div class="section-sub">Calculated in real-time from closing readings and prices.</div>
                </div>
            </div>
            <div class="cs-section-body">
                <div id="live-calculation" class="live-grid"></div>
                <div class="gross-total-row">
                    <span class="gt-label"><i class="bi bi-check-circle me-2"></i>Gross Sales Total</span>
                    <span class="gt-value">₱<span id="gross-total">0.00</span></span>
                </div>
            </div>
        </div>

        
        <div class="cs-section">
            <div class="cs-section-head">
                <div class="section-icon" style="background:#fce7f3; color:#db2777;"><i class="bi bi-percent"></i></div>
                <div>
                    <div class="section-title">Discounts</div>
                    <div class="section-sub">Record any discounts applied during this shift.</div>
                </div>
            </div>
            <div class="cs-section-body">
                <div style="overflow-x:auto;">
                    <table class="section-table" id="discount-table">
                        <thead>
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
                <button type="button" class="btn-add-row" onclick="addDiscountRow()">
                    <i class="bi bi-plus"></i> Add Discount
                </button>
            </div>
        </div>

        
        <div class="cs-section">
            <div class="cs-section-head">
                <div class="section-icon" style="background:#fee2e2; color:#ef4444;"><i class="bi bi-credit-card"></i></div>
                <div>
                    <div class="section-title">Credits</div>
                    <div class="section-sub">Record credit transactions issued during this shift.</div>
                </div>
            </div>
            <div class="cs-section-body">
                <div style="overflow-x:auto;">
                    <table class="section-table" id="credit-table">
                        <thead>
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
                <button type="button" class="btn-add-row" onclick="addCreditRow()">
                    <i class="bi bi-plus"></i> Add Credit
                </button>
            </div>
        </div>

        <div class="submit-row">
            <button type="submit" class="btn-close-shift">
                <i class="bi bi-stop-circle"></i> Close This Shift
            </button>
            <a href="<?php echo e(route('shift.management', ['view' => 'home'])); ?>" class="btn-cancel-link">Cancel</a>
        </div>
    </form>

    <?php endif; ?>
</div>

<script>
window.fuelOptions     = <?php echo json_encode($fuels->map(fn($f) => ['fuel_id' => $f->FuelID, 'fuel_name' => $f->fuel_name])); ?>;
window.customerOptions = <?php echo json_encode($customers->map(fn($c) => ['customer_id' => $c->CustomerID, 'customer_name' => $c->First_name . ' ' . ($c->Last_name ?? '')])); ?>;

(function () {
    const fmt  = (n) => new Intl.NumberFormat('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n);
    const fmt3 = (n) => new Intl.NumberFormat('en-PH', { minimumFractionDigits: 3, maximumFractionDigits: 3 }).format(n);

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
            if (fuelId && !prices[fuelId]) prices[fuelId] = parseFloat(el.value) || 0;
        });
        return prices;
    }

    function getPriceByFuelId(fuelId) {
        return getPricesByFuelId()[fuelId] || 0;
    }

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
            col.innerHTML = `
                <div class="live-card">
                    <div class="lc-label">${label}</div>
                    <div class="lc-liters">Liters: <strong>${fmt3(liters)}</strong></div>
                    <div class="lc-price">₱${fmt(price)}/L</div>
                    <div class="lc-gross">₱${fmt(gross)}</div>
                </div>`;
            panel.appendChild(col);
        });
        document.getElementById('gross-total').textContent = fmt(grandTotal);
    }

    document.querySelectorAll('.closing-reading, .price-input').forEach(el => {
        el.addEventListener('input', () => { recalc(); refreshRowPrices(); });
    });
    recalc();

    function refreshRowPrices() {
        document.querySelectorAll('#discounts-body tr, #credits-body tr').forEach(tr => {
            const fuelSel = tr.querySelector('select[name*="[fuel_id]"]');
            const priceEl = tr.querySelector('input[name*="[retail_price]"]');
            if (fuelSel && priceEl && fuelSel.value) {
                const fuelId = fuelSel.options[fuelSel.selectedIndex]?.dataset?.fuelId;
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

    const TS = (tag, cls, extra = '') => `<${tag} class="section-table ${cls}" ${extra}>`;

    let discountIndex = 0;
    window.addDiscountRow = function () {
        const i    = discountIndex++;
        const body = document.getElementById('discounts-body');
        const tr   = document.createElement('tr');
        tr.innerHTML = `
            <td><select name="discounts[${i}][fuel_id]" class="ts-select disc-fuel-${i}">${buildFuelOptions()}</select></td>
            <td><select name="discounts[${i}][customer_id]" class="ts-select">${buildCustomerOptions()}</select></td>
            <td><input type="number" step="0.001" name="discounts[${i}][liters]"             class="ts-input disc-liters-${i}"   value="0"></td>
            <td><input type="number" step="0.01"  name="discounts[${i}][retail_price]"       class="ts-input disc-price-${i}"    value="0"></td>
            <td><input type="number" step="0.01"  name="discounts[${i}][discount_per_liter]" class="ts-input disc-dpl-${i}"      value="0"></td>
            <td><input type="number" step="0.01"  name="discounts[${i}][discount_sale]"      class="ts-input disc-total-${i}"    value="0" readonly style="background:#f0fdf4; color:#10b981; font-weight:600;"></td>
            <td><input type="text"                name="discounts[${i}][description]"         class="ts-input" placeholder="Reason"></td>
            <td><button type="button" class="btn-remove-row" onclick="this.closest('tr').remove()">✕</button></td>`;
        body.appendChild(tr);

        const fuelSel  = tr.querySelector(`.disc-fuel-${i}`);
        const litersEl = tr.querySelector(`.disc-liters-${i}`);
        const priceEl  = tr.querySelector(`.disc-price-${i}`);
        const dplEl    = tr.querySelector(`.disc-dpl-${i}`);
        const totalEl  = tr.querySelector(`.disc-total-${i}`);

        fuelSel.addEventListener('change', function () {
            const fuelId = this.options[this.selectedIndex]?.dataset?.fuelId;
            if (fuelId) { const p = getPriceByFuelId(fuelId); if (p > 0) { priceEl.value = p.toFixed(2); autoCalc(); } }
        });
        const autoCalc = () => { totalEl.value = ((parseFloat(litersEl.value)||0) * (parseFloat(dplEl.value)||0)).toFixed(2); };
        litersEl.addEventListener('input', autoCalc);
        dplEl.addEventListener('input', autoCalc);

        if (fuelSel.options.length > 1) { fuelSel.selectedIndex = 1; fuelSel.dispatchEvent(new Event('change')); }
    };

    let creditIndex = 0;
    window.addCreditRow = function () {
        const i    = creditIndex++;
        const body = document.getElementById('credits-body');
        const tr   = document.createElement('tr');
        tr.innerHTML = `
            <td><select name="credits[${i}][fuel_id]" class="ts-select cred-fuel-${i}">${buildFuelOptions()}</select></td>
            <td><select name="credits[${i}][customer_id]" class="ts-select">${buildCustomerOptions()}</select></td>
            <td><input type="number" step="0.001" name="credits[${i}][liters]"           class="ts-input cred-liters-${i}" value=""></td>
            <td><input type="number" step="0.01"  name="credits[${i}][retail_price]"     class="ts-input cred-price-${i}"  value=""></td>
            <td><input type="number" step="0.01"  name="credits[${i}][retail_sale]"      class="ts-input cred-retail-${i}" value=""></td>
            <td style="text-align:center;"><input type="checkbox" name="credits[${i}][discounted]" class="cred-disc-chk-${i}" value="1" style="width:18px;height:18px;accent-color:#ef4444;"></td>
            <td><input type="number" step="0.01"  name="credits[${i}][discounted_sale]"  class="ts-input cred-dsale-${i}"  value="" disabled></td>
            <td><input type="text"                name="credits[${i}][description]"       class="ts-input" placeholder="Reason"></td>
            <td><button type="button" class="btn-remove-row" onclick="this.closest('tr').remove()">✕</button></td>`;
        body.appendChild(tr);

        const fuelSel  = tr.querySelector(`.cred-fuel-${i}`);
        const litersEl = tr.querySelector(`.cred-liters-${i}`);
        const priceEl  = tr.querySelector(`.cred-price-${i}`);
        const retailEl = tr.querySelector(`.cred-retail-${i}`);
        const chkEl    = tr.querySelector(`.cred-disc-chk-${i}`);
        const dsaleEl  = tr.querySelector(`.cred-dsale-${i}`);

        fuelSel.addEventListener('change', function () {
            const fuelId = this.options[this.selectedIndex]?.dataset?.fuelId;
            if (fuelId) { const p = getPriceByFuelId(fuelId); if (p > 0) { priceEl.value = p.toFixed(2); smartCalc('price'); } }
        });

        function smartCalc(changed) {
            const L = parseFloat(litersEl.value), P = parseFloat(priceEl.value), A = parseFloat(retailEl.value);
            const hasL = !isNaN(L) && litersEl.value !== '', hasP = !isNaN(P) && priceEl.value !== '', hasA = !isNaN(A) && retailEl.value !== '';
            if (changed === 'liters') {
                if (hasL && hasP) retailEl.value = (L * P).toFixed(2);
                else if (hasL && hasA) priceEl.value = L > 0 ? (A / L).toFixed(2) : '';
            } else if (changed === 'price') {
                if (hasP && hasL) retailEl.value = (L * P).toFixed(2);
                else if (hasP && hasA) litersEl.value = P > 0 ? (A / P).toFixed(3) : '';
            } else if (changed === 'amount') {
                if (hasA && hasL && L > 0) priceEl.value = (A / L).toFixed(2);
                else if (hasA && hasP && P > 0) litersEl.value = (A / P).toFixed(3);
            }
            if (chkEl.checked && (!dsaleEl.value || parseFloat(dsaleEl.value) === 0)) dsaleEl.value = retailEl.value;
        }

        litersEl.addEventListener('input', () => smartCalc('liters'));
        priceEl.addEventListener('input',  () => smartCalc('price'));
        retailEl.addEventListener('input', () => smartCalc('amount'));
        chkEl.addEventListener('change', function () {
            dsaleEl.disabled = !this.checked;
            if (!this.checked) dsaleEl.value = '';
            else if (retailEl.value) dsaleEl.value = retailEl.value;
        });

        if (fuelSel.options.length > 1) { fuelSel.selectedIndex = 1; fuelSel.dispatchEvent(new Event('change')); }
    };
})();
</script><?php /**PATH C:\Users\john mark bolanon\laravel\Seal-gasolineStation\resources\views/Shift/partials/close.blade.php ENDPATH**/ ?>