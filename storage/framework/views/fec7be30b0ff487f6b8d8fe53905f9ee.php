

<?php $__env->startSection('title', 'Totalizer Log'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .table th {
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748b;
        font-weight: 600;
    }
    .fuel-badge {
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 500;
        background: #fff0f2;
        color: #D2042D;
    }
    .date-main {
        font-weight: 600;
        color: #1e293b;
    }
    .date-sub {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    /* Fix pagination size */
    .pagination {
        margin-bottom: 0;
        font-size: 0.85rem;
    }
    .pagination .page-link {
        padding: 4px 10px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Totalizer Log</h4>
        <p class="text-muted mb-0">Closing totalizer readings recorded per shift</p>
    </div>
    <a href="<?php echo e(route('totalizer.export', request()->query())); ?>"
       class="btn btn-outline-danger">
        <i class="bi bi-download me-1"></i> Export CSV
    </a>
</div>


<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('totalizer.index')); ?>" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Pump</label>
                <select name="pump_id" class="form-select">
                    <option value="">All Pumps</option>
                    <?php $__currentLoopData = $pumps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pump): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($pump->PumpID); ?>" <?php echo e(request('pump_id') == $pump->PumpID ? 'selected' : ''); ?>>
                            <?php echo e($pump->pump_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Fuel</label>
                <select name="fuel_id" class="form-select">
                    <option value="">All Fuels</option>
                    <?php $__currentLoopData = $fuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fuel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($fuel->FuelID); ?>" <?php echo e(request('fuel_id') == $fuel->FuelID ? 'selected' : ''); ?>>
                            <?php echo e($fuel->fuel_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Date From</label>
                <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Date To</label>
                <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-danger w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="<?php echo e(route('totalizer.index')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-x"></i>
                </a>
            </div>
        </form>
    </div>
</div>


<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>


<div class="card shadow-sm">
    <div class="card-body">

        
        <?php if($logs->total() > 0): ?>
            <p class="text-muted small mb-3">
                Showing <?php echo e($logs->firstItem()); ?>–<?php echo e($logs->lastItem()); ?> of <?php echo e($logs->total()); ?> results
            </p>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date Recorded</th>
                        <th>Pump</th>
                        <th>Fuel Type</th>
                        <th class="text-end">Closing Reading (L)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <?php if($log->date_recorded): ?>
                                    <span class="date-main">
                                        <?php echo e(date('M d, Y', strtotime($log->date_recorded))); ?>

                                    </span>
                                    <?php if(!empty($log->closed_at)): ?>
                                        <br>
                                        <span class="date-sub">
                                            <i class="bi bi-clock me-1"></i>
                                            <?php echo e(date('h:i A', strtotime($log->closed_at))); ?>

                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($log->pump_name); ?></td>
                            <td><span class="fuel-badge"><?php echo e($log->fuel_name); ?></span></td>
                            <td class="text-end fw-semibold"><?php echo e(number_format($log->reading, 3)); ?> L</td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-speedometer2 fs-3 d-block mb-2"></i>
                                <strong>No totalizer records found.</strong>
                                <div class="small mt-1">Try adjusting your filters or close a shift to generate a log.</div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <span class="text-muted small">
                <?php if($logs->total() > 0): ?>
                    Page <?php echo e($logs->currentPage()); ?> of <?php echo e($logs->lastPage()); ?>

                <?php endif; ?>
            </span>
            <div>
                <?php echo e($logs->links('pagination::bootstrap-4')); ?>

            </div>
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\john mark bolanon\laravel\Seal-gasolineStation\resources\views/totalizer/index.blade.php ENDPATH**/ ?>