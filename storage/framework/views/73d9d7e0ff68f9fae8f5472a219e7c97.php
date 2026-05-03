
<?php if($activeShift): ?>
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        A shift is already open. Please close it first.
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h5>Current Open Shift Readings</h5>
            
            <button onclick="cancelOpenShift(<?php echo e($activeShift->ShiftID); ?>)" class="btn btn-outline-danger mt-3">
                Cancel Open Shift
            </button>
        </div>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-4">Open New Shift — Enter Opening Readings</h4>

            
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            
            <?php if(($pumps ?? collect())->isEmpty()): ?>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    No pumps found. Please add pumps and fuel types before opening a shift.
                </div>
            <?php else: ?>

            <form method="POST" action="<?php echo e(route('shift.open')); ?>">
                <?php echo csrf_field(); ?>

                <div class="row g-4">
                    <?php $__currentLoopData = $pumps ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pump): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6">
                            <div class="card pump-card">
                                <div class="card-header bg-light">
                                    <strong><?php echo e($pump->pump_name); ?></strong>
                                </div>
                                <div class="card-body">
                                    <?php $__currentLoopData = $pump->pumpFuels ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pumpFuel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="mb-3">
                                            <label class="form-label">
                                                <?php echo e($pumpFuel->fuel->fuel_name ?? 'Fuel'); ?> — Opening Reading (L)
                                            </label>
                                            
                                            <input type="number"
                                                   step="0.001"
                                                   name="opening_readings[<?php echo e($pumpFuel->PumpFuelID); ?>]"
                                                   class="form-control"
                                                   value="<?php echo e(old('opening_readings.'.$pumpFuel->PumpFuelID)); ?>"
                                                   placeholder="<?php echo e(number_format($pumpFuel->totalizer_reading, 3)); ?> (last reading)"
                                                   required>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <button type="submit" class="btn btn-success btn-lg mt-4 px-5">
                    <i class="bi bi-play-circle me-2"></i>Open Shift
                </button>
            </form>

            <?php endif; ?> 
        </div>
    </div>
<?php endif; ?><?php /**PATH C:\Users\john mark bolanon\laravel\Seal-gasolineStation\resources\views/Shift/partials/open.blade.php ENDPATH**/ ?>