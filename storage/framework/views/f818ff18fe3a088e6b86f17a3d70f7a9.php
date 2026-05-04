

<?php $__env->startSection('title', 'Fuels'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Fuel Types</h4>
        <p class="text-muted small mb-0">Manage available fuel types</p>
    </div>
    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addFuelModal">
        <i class="bi bi-plus-lg me-1"></i> Add Fuel
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


<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <?php if($fuels->isEmpty()): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-droplet" style="font-size:48px; opacity:.3;"></i>
                <p class="mt-3">No fuel types yet. Click <strong>Add Fuel</strong> to get started.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Fuel Name</th>
                            <th>Pumps Assigned</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $fuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fuel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $colors = [
                                'Premium' => ['bg' => '#fff3cd', 'text' => '#856404'],
                                'Diesel'  => ['bg' => '#d1e7dd', 'text' => '#0a3622'],
                                'Regular' => ['bg' => '#cfe2ff', 'text' => '#084298'],
                            ];
                            $color = $colors[$fuel->fuel_name] ?? ['bg' => '#e9ecef', 'text' => '#495057'];
                        ?>
                        <tr>
                            <td class="ps-4 text-muted small"><?php echo e($loop->iteration); ?></td>
                            <td>
                                <span class="badge rounded-pill px-3 py-2"
                                      style="background:<?php echo e($color['bg']); ?>;color:<?php echo e($color['text']); ?>;font-size:13px;font-weight:600;">
                                    <i class="bi bi-droplet-fill me-1"></i><?php echo e($fuel->fuel_name); ?>

                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary rounded-pill">
                                    <?php echo e($fuel->pumpFuels->count()); ?> pump(s)
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border-0 rounded-3" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                        <li>
                                            
                                            <a class="dropdown-item" href="#"
                                               onclick="openEditModal(
                                                   <?php echo e($fuel->FuelID); ?>,
                                                   '<?php echo e(addslashes($fuel->fuel_name)); ?>'
                                               )">
                                                <i class="bi bi-pencil me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#"
                                               onclick="deleteFuel(<?php echo e($fuel->FuelID); ?>, '<?php echo e(addslashes($fuel->fuel_name)); ?>')">
                                                <i class="bi bi-trash me-2"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>


<div class="modal fade" id="addFuelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form method="POST" action="<?php echo e(route('fuels.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Add Fuel Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Fuel Name <span class="text-danger">*</span></label>
                        <input type="text" name="fuel_name" class="form-control rounded-3"
                               placeholder="e.g. Premium, Diesel, Regular" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-3 px-4">Add Fuel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editFuelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form method="POST" id="editFuelForm">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Fuel Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Fuel Name <span class="text-danger">*</span></label>
                        <input type="text" name="fuel_name" id="edit_fuel_name"
                               class="form-control rounded-3" required>
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


<form id="deleteFuelForm" method="POST" style="display:none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Only fuel_name is passed — octane and description are not on the Fuel model
    function openEditModal(id, name) {
        document.getElementById('edit_fuel_name').value = name;
        document.getElementById('editFuelForm').action  = `/fuels/${id}`;
        new bootstrap.Modal(document.getElementById('editFuelModal')).show();
    }

    function deleteFuel(id, name) {
        if (!confirm(`Delete fuel "${name}"? This may affect pumps and shifts using this fuel.`)) return;
        const form = document.getElementById('deleteFuelForm');
        form.action = `/fuels/${id}`;
        form.submit();
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\john mark bolanon\laravel\Seal-gasolineStation\resources\views/Fuels/index.blade.php ENDPATH**/ ?>