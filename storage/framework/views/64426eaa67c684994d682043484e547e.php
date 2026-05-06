

<?php $__env->startSection('title', 'Edit Shift'); ?>
<?php $__env->startSection('subtitle', 'Edit shift record for ' . $shift->sales_date->format('M d, Y')); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?php echo e(route('shift.management', ['view' => 'home'])); ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
    </a>
    <h4 class="mb-0 fw-bold">Edit Shift — <?php echo e($shift->sales_date->format('M d, Y')); ?></h4>
    <span class="badge bg-<?php echo e($shift->status === 'open' ? 'success' : 'secondary'); ?> ms-2">
        <?php echo e(ucfirst($shift->status)); ?>

    </span>
</div>

<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
    </div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('shift.update', $shift->ShiftID)); ?>" id="edit-shift-form">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light fw-semibold">
            <i class="bi bi-speedometer2 me-2"></i>Shift Readings
        </div>
        <div class="card-body">
            <div class="row g-3">
                <?php $__currentLoopData = $shift->shiftReadings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reading): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $pf = \App\Models\PumpFuel::where('PumpID', $reading->PumpID)
                                ->where('FuelID', $reading->FuelID)->first();
                    ?>
                    <div class="col-md-4">
                        <div class="card border-0 bg-light p-3">
                            <div class="small text-muted fw-semibold mb-2">
                                <?php echo e($reading->pump->pump_name ?? 'Pump'); ?> — <?php echo e($reading->fuel->fuel_name ?? 'Fuel'); ?>

                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label small mb-1">Opening</label>
                                    <input type="number" step="0.001"
                                           name="readings[<?php echo e($reading->ShiftReadingID); ?>][opening_reading]"
                                           class="form-control form-control-sm"
                                           value="<?php echo e(old('readings.'.$reading->ShiftReadingID.'.opening_reading', $reading->opening_reading)); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-1">Closing</label>
                                    <input type="number" step="0.001"
                                           name="readings[<?php echo e($reading->ShiftReadingID); ?>][closing_reading]"
                                           class="form-control form-control-sm"
                                           value="<?php echo e(old('readings.'.$reading->ShiftReadingID.'.closing_reading', $reading->closing_reading)); ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small mb-1">Price/L (₱)</label>
                                    <input type="number" step="0.0001"
                                           name="readings[<?php echo e($reading->ShiftReadingID); ?>][price_per_liter]"
                                           class="form-control form-control-sm"
                                           value="<?php echo e(old('readings.'.$reading->ShiftReadingID.'.price_per_liter', $reading->price_per_liter ?? $pf?->price_per_liter)); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    
    <?php
        $existingDiscounts = collect();
        foreach($shift->sales as $sale) {
            $existingDiscounts = $existingDiscounts->merge($sale->salesDiscounts ?? []);
        }
    ?>
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
                        
                        <?php $__currentLoopData = $existingDiscounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr data-existing-id="<?php echo e($sd->SalesDiscountID); ?>">
                                <td>
                                    <select name="existing_discounts[<?php echo e($sd->SalesDiscountID); ?>][fuel_id]" class="form-select form-select-sm">
                                        <?php $__currentLoopData = $fuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($f->FuelID); ?>" <?php if($f->FuelID == $sd->FuelID): echo 'selected'; endif; ?>><?php echo e($f->fuel_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="existing_discounts[<?php echo e($sd->SalesDiscountID); ?>][customer_id]" class="form-select form-select-sm">
                                        <option value="">— None —</option>
                                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($c->CustomerID); ?>" <?php if($c->CustomerID == $sd->CustomerID): echo 'selected'; endif; ?>><?php echo e($c->First_name); ?> <?php echo e($c->Last_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </td>
                                <td><input type="number" step="0.001" name="existing_discounts[<?php echo e($sd->SalesDiscountID); ?>][liters]" class="form-control form-control-sm ed-liters-<?php echo e($sd->SalesDiscountID); ?>" value="<?php echo e($sd->liters); ?>"></td>
                                <td><input type="number" step="0.01"  name="existing_discounts[<?php echo e($sd->SalesDiscountID); ?>][retail_price]" class="form-control form-control-sm" value="<?php echo e($sd->retail_price); ?>"></td>
                                <td><input type="number" step="0.01"  name="existing_discounts[<?php echo e($sd->SalesDiscountID); ?>][discount_per_liter]" class="form-control form-control-sm ed-dpl-<?php echo e($sd->SalesDiscountID); ?>" value="<?php echo e($sd->discount_per_liter); ?>"></td>
                                <td><input type="number" step="0.01"  name="existing_discounts[<?php echo e($sd->SalesDiscountID); ?>][discount_sale]" class="form-control form-control-sm ed-total-<?php echo e($sd->SalesDiscountID); ?>" value="<?php echo e($sd->discount_sale); ?>" readonly></td>
                                <td><input type="text" name="existing_discounts[<?php echo e($sd->SalesDiscountID); ?>][description]" class="form-control form-control-sm" value="<?php echo e($sd->description); ?>" placeholder="Reason"></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="deleteExistingDiscount(<?php echo e($sd->SalesDiscountID); ?>, this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <input type="hidden" name="existing_discounts[<?php echo e($sd->SalesDiscountID); ?>][_delete]" value="0" class="delete-flag-d-<?php echo e($sd->SalesDiscountID); ?>">
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addDiscountRow()">
                <i class="bi bi-plus me-1"></i>Add Discount
            </button>
        </div>
    </div>

    
    <?php
        $existingCredits = collect();
        foreach($shift->sales as $sale) {
            $existingCredits = $existingCredits->merge($sale->salesCredits ?? []);
        }
    ?>
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
                        
                        <?php $__currentLoopData = $existingCredits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr data-existing-id="<?php echo e($sc->SalesCreditID); ?>">
                                <td>
                                    <select name="existing_credits[<?php echo e($sc->SalesCreditID); ?>][fuel_id]" class="form-select form-select-sm">
                                        <?php $__currentLoopData = $fuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($f->FuelID); ?>" <?php if($f->FuelID == ($sc->credit->FuelID ?? null)): echo 'selected'; endif; ?>><?php echo e($f->fuel_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="existing_credits[<?php echo e($sc->SalesCreditID); ?>][customer_id]" class="form-select form-select-sm">
                                        <option value="">— None —</option>
                                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($c->CustomerID); ?>" <?php if($c->CustomerID == $sc->CustomerID): echo 'selected'; endif; ?>><?php echo e($c->First_name); ?> <?php echo e($c->Last_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </td>
                                <td><input type="number" step="0.001" name="existing_credits[<?php echo e($sc->SalesCreditID); ?>][liters]"        class="form-control form-control-sm ec-liters-<?php echo e($sc->SalesCreditID); ?>"  value="<?php echo e($sc->liters); ?>"></td>
                                <td><input type="number" step="0.01"  name="existing_credits[<?php echo e($sc->SalesCreditID); ?>][retail_price]"  class="form-control form-control-sm ec-price-<?php echo e($sc->SalesCreditID); ?>"   value="<?php echo e($sc->retail_price); ?>"></td>
                                <td><input type="number" step="0.01"  name="existing_credits[<?php echo e($sc->SalesCreditID); ?>][retail_sale]"   class="form-control form-control-sm ec-retail-<?php echo e($sc->SalesCreditID); ?>"  value="<?php echo e($sc->retail_sale); ?>"></td>
                                <td class="text-center">
                                    <input type="checkbox" name="existing_credits[<?php echo e($sc->SalesCreditID); ?>][discounted]" class="form-check-input ec-chk-<?php echo e($sc->SalesCreditID); ?>" value="1" <?php if($sc->discounted): echo 'checked'; endif; ?>>
                                </td>
                                <td><input type="number" step="0.01"  name="existing_credits[<?php echo e($sc->SalesCreditID); ?>][discounted_sale]" class="form-control form-control-sm ec-dsale-<?php echo e($sc->SalesCreditID); ?>" value="<?php echo e($sc->discounted_sale); ?>" <?php if(!$sc->discounted): echo 'disabled'; endif; ?>></td>
                                <td><input type="text" name="existing_credits[<?php echo e($sc->SalesCreditID); ?>][description]" class="form-control form-control-sm" value="<?php echo e($sc->description); ?>" placeholder="Reason"></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="deleteExistingCredit(<?php echo e($sc->SalesCreditID); ?>, this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <input type="hidden" name="existing_credits[<?php echo e($sc->SalesCreditID); ?>][_delete]" value="0" class="delete-flag-c-<?php echo e($sc->SalesCreditID); ?>">
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
        <a href="<?php echo e(route('shift.management', ['view' => 'home'])); ?>" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<script>
window.fuelOptions     = <?php echo json_encode($fuels->map(fn($f) => ['fuel_id' => $f->FuelID, 'fuel_name' => $f->fuel_name])); ?>;
window.customerOptions = <?php echo json_encode($customers->map(fn($c) => ['customer_id' => $c->CustomerID, 'customer_name' => $c->First_name . ' ' . ($c->Last_name ?? '')])); ?>;

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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\john mark bolanon\laravel\Seal-gasolineStation\resources\views/Shift/edit.blade.php ENDPATH**/ ?>