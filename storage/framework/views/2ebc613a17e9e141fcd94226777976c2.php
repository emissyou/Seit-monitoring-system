

<?php $__env->startSection('title', 'Pumps'); ?>

<?php $__env->startSection('content'); ?>


<div class="d-flex align-items-start justify-content-between mb-4">
    <div style="border-left: 3px solid #E53935; padding-left: 14px;">
        <p class="text-uppercase mb-1" style="font-size:11px; font-weight:600; letter-spacing:.08em; color:#E53935;">
            Management &bull; Pumps
        </p>
        <h4 class="fw-bold mb-0" style="font-size:1.5rem; color:#111;">Pumps</h4>
        <p class="mb-0" style="font-size:13px; color:#888;">Manage pumps and their fuel types</p>
    </div>
    <button class="btn d-flex align-items-center gap-2 px-3 py-2 rounded-3 shadow-sm"
            style="background:#E53935; border-color:#E53935; color:#fff; font-size:14px; font-weight:500;"
            data-bs-toggle="modal" data-bs-target="#addPumpModal">
        <i class="bi bi-plus-lg"></i> Add Pump
    </button>
</div>


<?php if(session('success')): ?>
    <div class="alert alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert"
         style="background:#d1fae5; color:#065f46; font-size:14px;">
        <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="alert alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert"
         style="background:#fee2e2; color:#991b1b; font-size:14px;">
        <i class="bi bi-exclamation-circle-fill me-2"></i><?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>


<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden position-relative" style="background:#fff;">
            <div class="position-absolute top-0 end-0 mt-2 me-2"
                 style="width:80px;height:80px;background:rgba(229,57,53,.10);border-radius:50%;"></div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-3"
                         style="width:38px;height:38px;background:rgba(229,57,53,.10);">
                        <i class="bi bi-fuel-pump-fill" style="color:#E53935;font-size:16px;"></i>
                    </div>
                </div>
                <p class="text-uppercase mb-1" style="font-size:10px;font-weight:700;letter-spacing:.08em;color:#999;">
                    Total Pumps
                </p>
                <h3 class="fw-bold mb-0" style="font-size:1.6rem;color:#111;"><?php echo e($pumps->count()); ?></h3>
                <p class="mb-0 mt-1" style="font-size:12px;color:#aaa;">configured pumps</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden position-relative" style="background:#fff;">
            <div class="position-absolute top-0 end-0 mt-2 me-2"
                 style="width:80px;height:80px;background:rgba(16,185,129,.10);border-radius:50%;"></div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-3"
                         style="width:38px;height:38px;background:rgba(16,185,129,.10);">
                        <i class="bi bi-droplet-fill" style="color:#10b981;font-size:16px;"></i>
                    </div>
                </div>
                <p class="text-uppercase mb-1" style="font-size:10px;font-weight:700;letter-spacing:.08em;color:#999;">
                    Fuel Assignments
                </p>
                <h3 class="fw-bold mb-0" style="font-size:1.6rem;color:#111;">
                    <?php echo e($pumps->sum(fn($p) => $p->pumpFuels->count())); ?>

                </h3>
                <p class="mb-0 mt-1" style="font-size:12px;color:#aaa;">across all pumps</p>
            </div>
        </div>
    </div>
</div>


<?php if($pumps->isEmpty()): ?>
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body text-center py-5" style="color:#bbb;">
            <i class="bi bi-fuel-pump" style="font-size:52px;opacity:.25;"></i>
            <p class="mt-3 mb-0" style="font-size:14px;">No pumps yet.</p>
            <p style="font-size:13px;color:#ccc;">Click <strong style="color:#E53935;">Add Pump</strong> to get started.</p>
        </div>
    </div>
<?php else: ?>
    <div class="row g-3">
        <?php $__currentLoopData = $pumps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pump): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 rounded-4 shadow-sm h-100" style="background:#fff;">
                <div class="card-body p-4">

                    
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:44px;height:44px;background:rgba(229,57,53,.10);border-radius:12px;
                                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-fuel-pump-fill" style="color:#E53935;font-size:20px;"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0" style="font-size:15px;color:#111;"><?php echo e($pump->pump_name); ?></h6>
                                <span style="font-size:12px;color:#aaa;"><?php echo e($pump->pumpFuels->count()); ?> fuel type(s)</span>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm rounded-3 border-0"
                                    style="background:#f9f9f9;width:32px;height:32px;padding:0;
                                           display:inline-flex;align-items:center;justify-content:center;"
                                    data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical" style="color:#888;font-size:14px;"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 py-1"
                                style="min-width:150px;font-size:13px;">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-3"
                                       style="color:#374151;" href="#"
                                       onclick="openEditModal(
                                           <?php echo e($pump->pumpID); ?>,
                                           '<?php echo e(addslashes($pump->pump_name)); ?>',
                                           <?php echo $pump->pumpFuels->mapWithKeys(fn($pf) => [$pf->FuelID => $pf->price_per_liter])->toJson(); ?>

                                       )">
                                        <i class="bi bi-pencil" style="color:#6b7280;"></i> Edit
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1" style="border-color:#f0f0f0;"></li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-3"
                                       style="color:#E53935;" href="#"
                                       onclick="deletePump(<?php echo e($pump->pumpID); ?>, '<?php echo e(addslashes($pump->pump_name)); ?>')">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    
                    <div style="height:1px;background:#f3f4f6;margin-bottom:12px;"></div>

                    
                    <div class="d-flex flex-wrap gap-2">
                        <?php $__empty_1 = true; $__currentLoopData = $pump->pumpFuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $colors = [
                                    'Premium' => ['bg' => '#fff8e1', 'text' => '#b45309', 'dot' => '#f59e0b'],
                                    'Diesel'  => ['bg' => '#ecfdf5', 'text' => '#065f46', 'dot' => '#10b981'],
                                    'Regular' => ['bg' => '#eff6ff', 'text' => '#1d4ed8', 'dot' => '#3b82f6'],
                                ];
                                $name  = $pf->fuel->fuel_name ?? 'Unknown';
                                $color = $colors[$name] ?? ['bg' => '#f3f4f6', 'text' => '#374151', 'dot' => '#9ca3af'];
                            ?>
                            <span class="d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill"
                                  style="background:<?php echo e($color['bg']); ?>;color:<?php echo e($color['text']); ?>;font-size:12px;font-weight:600;">
                                <span style="width:6px;height:6px;border-radius:50%;background:<?php echo e($color['dot']); ?>;display:inline-block;flex-shrink:0;"></span>
                                <?php echo e($name); ?>

                                <span style="opacity:.45;margin:0 2px;">·</span>
                                ₱<?php echo e(number_format($pf->price_per_liter, 2)); ?>/L
                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <span style="font-size:12px;color:#bbb;font-style:italic;">No fuel types assigned</span>
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
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <form method="POST" action="<?php echo e(route('pumps.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header border-0 px-4 pt-4 pb-2">
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="font-size:1.1rem;color:#111;">Add Pump</h5>
                        <p class="mb-0 mt-1" style="font-size:12px;color:#aaa;">Configure a new pump and assign fuel types.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">

                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;color:#374151;">
                            Pump Name <span style="color:#E53935;">*</span>
                        </label>
                        <input type="text" name="pump_name"
                               class="form-control rounded-3 border-0"
                               style="background:#f9f9f9;font-size:14px;padding:.65rem 1rem;box-shadow:none;"
                               placeholder="e.g. Pump 1" value="<?php echo e(old('pump_name')); ?>" required>
                    </div>

                    
                    <div class="mb-2">
                        <label class="form-label fw-semibold" style="font-size:13px;color:#374151;">
                            Fuel Types &amp; Price per Liter
                        </label>
                        <div class="d-flex flex-column gap-2 mt-2">
                            <?php $__currentLoopData = $fuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fuel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $fc  = ['Premium'=>['dot'=>'#f59e0b'],'Diesel'=>['dot'=>'#10b981'],'Regular'=>['dot'=>'#3b82f6']];
                                $dot = $fc[$fuel->fuel_name]['dot'] ?? '#9ca3af';
                            ?>
                            <div class="rounded-3 p-3" style="background:#f9f9f9;">
                                <div class="form-check mb-0 d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox"
                                           name="fuel_ids[]"
                                           value="<?php echo e($fuel->FuelID); ?>"
                                           id="add_fuel_<?php echo e($fuel->FuelID); ?>"
                                           onchange="togglePrice(this, 'add_price_wrap_<?php echo e($fuel->FuelID); ?>')">
                                    <label class="form-check-label d-flex align-items-center gap-2 fw-medium"
                                           for="add_fuel_<?php echo e($fuel->FuelID); ?>"
                                           style="font-size:13px;color:#374151;cursor:pointer;">
                                        <span style="width:8px;height:8px;border-radius:50%;background:<?php echo e($dot); ?>;display:inline-block;"></span>
                                        <?php echo e($fuel->fuel_name); ?>

                                    </label>
                                </div>
                                <div id="add_price_wrap_<?php echo e($fuel->FuelID); ?>" class="d-none mt-2 ps-4">
                                    <div class="input-group input-group-sm" style="max-width:220px;">
                                        <span class="input-group-text border-0 rounded-start-3"
                                              style="background:#efefef;font-size:13px;color:#666;">₱</span>
                                        <input type="number"
                                               name="prices[<?php echo e($fuel->FuelID); ?>]"
                                               id="add_price_<?php echo e($fuel->FuelID); ?>"
                                               class="form-control border-0"
                                               style="background:#efefef;font-size:13px;box-shadow:none;"
                                               value="0" min="0" step="0.0001" placeholder="0.00">
                                        <span class="input-group-text border-0 rounded-end-3"
                                              style="background:#efefef;font-size:12px;color:#999;">/liter</span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php if($fuels->isEmpty()): ?>
                            <p class="mt-2" style="font-size:12px;color:#bbb;">No fuels found. Add fuels first.</p>
                        <?php endif; ?>
                    </div>

                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                    <button type="button" class="btn rounded-3 px-4"
                            style="background:#f3f4f6;color:#374151;font-size:13px;font-weight:500;border:none;"
                            data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn rounded-3 px-4"
                            style="background:#E53935;color:#fff;font-size:13px;font-weight:500;border:none;">
                        Add Pump
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editPumpModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <form method="POST" id="editPumpForm">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-header border-0 px-4 pt-4 pb-2">
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="font-size:1.1rem;color:#111;">Edit Pump</h5>
                        <p class="mb-0 mt-1" style="font-size:12px;color:#aaa;">Update the pump name and fuel assignments.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">

                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;color:#374151;">
                            Pump Name <span style="color:#E53935;">*</span>
                        </label>
                        <input type="text" name="pump_name" id="edit_pump_name"
                               class="form-control rounded-3 border-0"
                               style="background:#f9f9f9;font-size:14px;padding:.65rem 1rem;box-shadow:none;" required>
                    </div>

                    
                    <div class="mb-2">
                        <label class="form-label fw-semibold" style="font-size:13px;color:#374151;">
                            Fuel Types &amp; Price per Liter
                        </label>
                        <div class="d-flex flex-column gap-2 mt-2">
                            <?php $__currentLoopData = $fuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fuel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $fc  = ['Premium'=>['dot'=>'#f59e0b'],'Diesel'=>['dot'=>'#10b981'],'Regular'=>['dot'=>'#3b82f6']];
                                $dot = $fc[$fuel->fuel_name]['dot'] ?? '#9ca3af';
                            ?>
                            <div class="rounded-3 p-3" style="background:#f9f9f9;">
                                <div class="form-check mb-0 d-flex align-items-center gap-2">
                                    <input class="form-check-input edit-fuel-check" type="checkbox"
                                           name="fuel_ids[]"
                                           value="<?php echo e($fuel->FuelID); ?>"
                                           id="edit_fuel_<?php echo e($fuel->FuelID); ?>"
                                           onchange="togglePrice(this, 'edit_price_wrap_<?php echo e($fuel->FuelID); ?>')">
                                    <label class="form-check-label d-flex align-items-center gap-2 fw-medium"
                                           for="edit_fuel_<?php echo e($fuel->FuelID); ?>"
                                           style="font-size:13px;color:#374151;cursor:pointer;">
                                        <span style="width:8px;height:8px;border-radius:50%;background:<?php echo e($dot); ?>;display:inline-block;"></span>
                                        <?php echo e($fuel->fuel_name); ?>

                                    </label>
                                </div>
                                <div id="edit_price_wrap_<?php echo e($fuel->FuelID); ?>" class="d-none mt-2 ps-4">
                                    <div class="input-group input-group-sm" style="max-width:220px;">
                                        <span class="input-group-text border-0 rounded-start-3"
                                              style="background:#efefef;font-size:13px;color:#666;">₱</span>
                                        <input type="number"
                                               name="prices[<?php echo e($fuel->FuelID); ?>]"
                                               id="edit_price_<?php echo e($fuel->FuelID); ?>"
                                               class="form-control border-0"
                                               style="background:#efefef;font-size:13px;box-shadow:none;"
                                               value="0" min="0" step="0.0001" placeholder="0.00">
                                        <span class="input-group-text border-0 rounded-end-3"
                                              style="background:#efefef;font-size:12px;color:#999;">/liter</span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php if($fuels->isEmpty()): ?>
                            <p class="mt-2" style="font-size:12px;color:#bbb;">No fuels found. Add fuels first.</p>
                        <?php endif; ?>
                    </div>

                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                    <button type="button" class="btn rounded-3 px-4"
                            style="background:#f3f4f6;color:#374151;font-size:13px;font-weight:500;border:none;"
                            data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn rounded-3 px-4"
                            style="background:#E53935;color:#fff;font-size:13px;font-weight:500;border:none;">
                        Save Changes
                    </button>
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

    function openEditModal(id, name, fuelPrices) {
        document.getElementById('edit_pump_name').value = name;
        document.getElementById('editPumpForm').action  = `/pumps/${id}`;

        document.querySelectorAll('.edit-fuel-check').forEach(cb => {
            const fuelId     = parseInt(cb.value);
            const wrapId     = `edit_price_wrap_${fuelId}`;
            const priceId    = `edit_price_${fuelId}`;
            const wrap       = document.getElementById(wrapId);
            const priceInput = document.getElementById(priceId);

            if (fuelPrices.hasOwnProperty(fuelId)) {
                cb.checked = true;
                if (wrap)       wrap.classList.remove('d-none');
                if (priceInput) priceInput.value = fuelPrices[fuelId];
            } else {
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