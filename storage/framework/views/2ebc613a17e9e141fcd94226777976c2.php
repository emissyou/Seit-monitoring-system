

<?php $__env->startSection('title', 'Pumps'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Pumps</h4>
        <p class="text-muted small mb-0">Manage pumps and their fuel types</p>
    </div>
    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addPumpModal">
        <i class="bi bi-plus-lg me-1"></i> Add Pump
    </button>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>


<?php if($pumps->isEmpty()): ?>
    <div class="text-center py-5 text-muted">
        <i class="bi bi-fuel-pump" style="font-size:48px; opacity:.3;"></i>
        <p class="mt-3">No pumps yet. Click <strong>Add Pump</strong> to get started.</p>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php $__currentLoopData = $pumps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pump): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100 border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:44px;height:44px;background:#fff0f2;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-fuel-pump-fill text-danger" style="font-size:20px;"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0"><?php echo e($pump->pump_name); ?></h6>
                                <span class="text-muted small"><?php echo e($pump->pumpFuels->count()); ?> fuel type(s)</span>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border-0 rounded-3" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li>
                                    <a class="dropdown-item" href="#"
                                        onclick="openEditModal(
                                            <?php echo e($pump->pumpID); ?>,
                                            '<?php echo e(addslashes($pump->pump_name)); ?>',
                                            <?php echo $pump->pumpFuels->mapWithKeys(fn($pf) => [$pf->FuelID => $pf->price_per_liter])->toJson(); ?>

                                        )">
                                        <i class="bi bi-pencil me-2"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#"
                                        onclick="deletePump(<?php echo e($pump->pumpID); ?>, '<?php echo e(addslashes($pump->pump_name)); ?>')">
                                        <i class="bi bi-trash me-2"></i> Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <?php $__empty_1 = true; $__currentLoopData = $pump->pumpFuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $colors = [
                                    'Premium' => ['bg' => '#fff3cd', 'text' => '#856404'],
                                    'Diesel'  => ['bg' => '#d1e7dd', 'text' => '#0a3622'],
                                    'Regular' => ['bg' => '#cfe2ff', 'text' => '#084298'],
                                ];
                                $name  = $pf->fuel->fuel_name ?? 'Unknown';
                                $color = $colors[$name] ?? ['bg' => '#e9ecef', 'text' => '#495057'];
                            ?>
                            <span class="badge rounded-pill px-3 py-2"
                                  style="background:<?php echo e($color['bg']); ?>;color:<?php echo e($color['text']); ?>;font-size:12px;font-weight:600;">
                                <i class="bi bi-droplet-fill me-1"></i><?php echo e($name); ?>

                                &nbsp;·&nbsp;₱<?php echo e(number_format($pf->price_per_liter, 2)); ?>/L
                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <span class="text-muted small fst-italic">No fuel types assigned</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>


<div class="modal fade" id="addPumpModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form method="POST" action="<?php echo e(route('pumps.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Add Pump</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">

                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pump Name</label>
                        <input type="text" name="pump_name" class="form-control rounded-3"
                               placeholder="e.g. Pump 1" value="<?php echo e(old('pump_name')); ?>" required>
                    </div>

                    
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Fuel Types & Price per Liter</label>
                        <div class="d-flex flex-column gap-3 mt-1">
                            <?php $__currentLoopData = $fuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fuel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div>
                                    
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox"
                                               name="fuel_ids[]"
                                               value="<?php echo e($fuel->FuelID); ?>"
                                               id="add_fuel_<?php echo e($fuel->FuelID); ?>"
                                               onchange="togglePrice(this, 'add_price_wrap_<?php echo e($fuel->FuelID); ?>')">
                                        <label class="form-check-label fw-medium" for="add_fuel_<?php echo e($fuel->FuelID); ?>">
                                            <?php echo e($fuel->fuel_name); ?>

                                        </label>
                                    </div>
                                    
                                    <div id="add_price_wrap_<?php echo e($fuel->FuelID); ?>" class="d-none ps-4">
                                        <div class="input-group input-group-sm" style="max-width:220px;">
                                            <span class="input-group-text rounded-start-3">₱</span>
                                            <input type="number"
                                                   name="prices[<?php echo e($fuel->FuelID); ?>]"
                                                   id="add_price_<?php echo e($fuel->FuelID); ?>"
                                                   class="form-control rounded-end-3"
                                                   value="0"
                                                   min="0"
                                                   step="0.0001"
                                                   placeholder="0.00">
                                            <span class="input-group-text rounded-end-3 border-start-0 bg-white text-muted"
                                                  style="font-size:12px;">/liter</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php if($fuels->isEmpty()): ?>
                            <p class="text-muted small mt-1">No fuels found. Add fuels first.</p>
                        <?php endif; ?>
                    </div>

                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-3 px-4">Add Pump</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editPumpModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form method="POST" id="editPumpForm">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Pump</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">

                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pump Name</label>
                        <input type="text" name="pump_name" id="edit_pump_name"
                               class="form-control rounded-3" required>
                    </div>

                    
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Fuel Types & Price per Liter</label>
                        <div class="d-flex flex-column gap-3 mt-1">
                            <?php $__currentLoopData = $fuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fuel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div>
                                    
                                    <div class="form-check mb-1">
                                        <input class="form-check-input edit-fuel-check" type="checkbox"
                                               name="fuel_ids[]"
                                               value="<?php echo e($fuel->FuelID); ?>"
                                               id="edit_fuel_<?php echo e($fuel->FuelID); ?>"
                                               onchange="togglePrice(this, 'edit_price_wrap_<?php echo e($fuel->FuelID); ?>')">
                                        <label class="form-check-label fw-medium" for="edit_fuel_<?php echo e($fuel->FuelID); ?>">
                                            <?php echo e($fuel->fuel_name); ?>

                                        </label>
                                    </div>
                                    
                                    <div id="edit_price_wrap_<?php echo e($fuel->FuelID); ?>" class="d-none ps-4">
                                        <div class="input-group input-group-sm" style="max-width:220px;">
                                            <span class="input-group-text rounded-start-3">₱</span>
                                            <input type="number"
                                                   name="prices[<?php echo e($fuel->FuelID); ?>]"
                                                   id="edit_price_<?php echo e($fuel->FuelID); ?>"
                                                   class="form-control rounded-end-3"
                                                   value="0"
                                                   min="0"
                                                   step="0.0001"
                                                   placeholder="0.00">
                                            <span class="input-group-text rounded-end-3 border-start-0 bg-white text-muted"
                                                  style="font-size:12px;">/liter</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php if($fuels->isEmpty()): ?>
                            <p class="text-muted small mt-1">No fuels found. Add fuels first.</p>
                        <?php endif; ?>
                    </div>

                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-3 px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<form id="deletePumpForm" method="POST" style="display:none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    /**
     * Show or hide the price input wrapper when a fuel checkbox is toggled.
     * Also resets the price to 0 when unchecked.
     */
    function togglePrice(checkbox, wrapperId) {
        const wrap = document.getElementById(wrapperId);
        if (!wrap) return;
        if (checkbox.checked) {
            wrap.classList.remove('d-none');
        } else {
            wrap.classList.add('d-none');
            const input = wrap.querySelector('input[type="number"]');
            if (input) input.value = 0;
        }
    }

    /**
     * Open the Edit Pump modal.
     * @param {number} id        - pump pumpID
     * @param {string} name      - pump_name
     * @param {object} fuelPrices - { FuelID: price_per_liter, ... }
     */
    function openEditModal(id, name, fuelPrices) {
        document.getElementById('edit_pump_name').value = name;
        document.getElementById('editPumpForm').action  = `/pumps/${id}`;

        // Reset all checkboxes and price inputs first
        document.querySelectorAll('.edit-fuel-check').forEach(cb => {
            const fuelId   = parseInt(cb.value);
            const wrapId   = `edit_price_wrap_${fuelId}`;
            const priceId  = `edit_price_${fuelId}`;
            const wrap     = document.getElementById(wrapId);
            const priceInput = document.getElementById(priceId);

            if (fuelPrices.hasOwnProperty(fuelId)) {
                // This fuel is assigned to the pump
                cb.checked = true;
                if (wrap)       wrap.classList.remove('d-none');
                if (priceInput) priceInput.value = fuelPrices[fuelId];
            } else {
                // Not assigned
                cb.checked = false;
                if (wrap)       wrap.classList.add('d-none');
                if (priceInput) priceInput.value = 0;
            }
        });

        new bootstrap.Modal(document.getElementById('editPumpModal')).show();
    }

    function deletePump(id, name) {
        if (!confirm(`Delete pump "${name}"? This will also remove its fuel assignments.`)) return;
        const form = document.getElementById('deletePumpForm');
        form.action = `/pumps/${id}`;
        form.submit();
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\john mark bolanon\laravel\Seal-gasolineStation\resources\views/Pumps/index.blade.php ENDPATH**/ ?>