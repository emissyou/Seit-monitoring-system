

<?php $__env->startSection('title', 'Shift Detail'); ?>
<?php $__env->startSection('subtitle', 'Shift on ' . $shift->sales_date->format('F d, Y')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .detail-label {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #94a3b8;
    }
    .detail-value {
        font-size: 1.05rem;
        font-weight: 600;
        color: #1e293b;
    }
    .section-heading {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #64748b;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }
    .kpi-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }
    .kpi-card .kpi-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #94a3b8;
    }
    .kpi-card .kpi-value {
        font-size: 1.6rem;
        font-weight: 800;
        line-height: 1.2;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?php echo e(route('shift.management', ['view' => 'home'])); ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
    <div>
        <h4 class="mb-0 fw-bold">Shift — <?php echo e($shift->sales_date->format('F d, Y')); ?></h4>
        <small class="text-muted">
            Shift #<?php echo e($shift->ShiftID); ?> &nbsp;·&nbsp;
            <span class="badge bg-<?php echo e($shift->status === 'open' ? 'success' : 'secondary'); ?>">
                <?php echo e(ucfirst($shift->status)); ?>

            </span>
            <?php if($shift->archived): ?>
                <span class="badge bg-warning text-dark ms-1">Archived</span>
            <?php endif; ?>
        </small>
    </div>
</div>


<?php
    $readings  = $shift->shiftReadings;
    $sales     = $shift->sales;
    $allDisc   = $sales->flatMap(fn($s) => $s->salesDiscounts);
    $allCred   = $sales->flatMap(fn($s) => $s->salesCredits);

    $totalLiters  = $readings->sum(fn($r) => max(0, ($r->closing_reading ?? 0) - $r->opening_reading));
    $totalGross   = $readings->sum(fn($r) => max(0, ($r->closing_reading ?? 0) - $r->opening_reading) * ($r->price_per_liter ?? 0));
    $totalDisc    = $allDisc->sum('discount_sale');
    $totalCredit  = $allCred->sum(fn($c) => $c->discounted ? $c->discounted_sale : $c->retail_sale);
    $totalNet     = $totalGross - $totalDisc;
    $regCredit    = $allCred->where('discounted', false)->sum('retail_sale');
    $cashInHand   = $totalNet - $regCredit;
?>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2">
        <div class="card kpi-card h-100 p-3">
            <div class="kpi-label">Liters</div>
            <div class="kpi-value text-primary"><?php echo e(number_format($totalLiters, 3)); ?></div>
            <small class="text-muted">L</small>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card kpi-card h-100 p-3">
            <div class="kpi-label">Gross</div>
            <div class="kpi-value">₱<?php echo e(number_format($totalGross, 2)); ?></div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card kpi-card h-100 p-3">
            <div class="kpi-label">Discount</div>
            <div class="kpi-value text-warning">₱<?php echo e(number_format($totalDisc, 2)); ?></div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card kpi-card h-100 p-3">
            <div class="kpi-label">Credit</div>
            <div class="kpi-value text-danger">₱<?php echo e(number_format($totalCredit, 2)); ?></div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card kpi-card h-100 p-3">
            <div class="kpi-label">Net Sales</div>
            <div class="kpi-value text-success">₱<?php echo e(number_format($totalNet, 2)); ?></div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card kpi-card h-100 p-3">
            <div class="kpi-label">Cash in Hand</div>
            <div class="kpi-value">₱<?php echo e(number_format($cashInHand, 2)); ?></div>
        </div>
    </div>
</div>

<div class="row g-4">

    
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="section-heading">Shift Info</div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="detail-label">Opened At</div>
                        <div class="detail-value"><?php echo e($shift->opened_at?->format('h:i A') ?? '—'); ?></div>
                    </div>
                    <div class="col-6">
                        <div class="detail-label">Closed At</div>
                        <div class="detail-value"><?php echo e($shift->closed_at?->format('h:i A') ?? '—'); ?></div>
                    </div>
                    <div class="col-6">
                        <div class="detail-label">Date</div>
                        <div class="detail-value"><?php echo e($shift->sales_date->format('M d, Y')); ?></div>
                    </div>
                    <div class="col-6">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="badge bg-<?php echo e($shift->status === 'open' ? 'success' : 'secondary'); ?> fs-6">
                                <?php echo e(ucfirst($shift->status)); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="section-heading">Pump Readings</div>
                <?php if($readings->isEmpty()): ?>
                    <p class="text-muted">No readings recorded.</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Pump</th>
                                <th>Fuel</th>
                                <th class="text-end">Opening</th>
                                <th class="text-end">Closing</th>
                                <th class="text-end">Liters Sold</th>
                                <th class="text-end">Price/L</th>
                                <th class="text-end">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $readings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $liters = max(0, ($r->closing_reading ?? 0) - $r->opening_reading);
                                    $value  = $liters * ($r->price_per_liter ?? 0);
                                ?>
                                <tr>
                                    <td><?php echo e($r->pump->pump_name ?? '—'); ?></td>
                                    <td><?php echo e($r->fuel->fuel_name ?? '—'); ?></td>
                                    <td class="text-end"><?php echo e(number_format($r->opening_reading, 3)); ?></td>
                                    <td class="text-end"><?php echo e($r->closing_reading !== null ? number_format($r->closing_reading, 3) : '—'); ?></td>
                                    <td class="text-end fw-semibold text-primary"><?php echo e(number_format($liters, 3)); ?> L</td>
                                    <td class="text-end">₱<?php echo e(number_format($r->price_per_liter ?? 0, 2)); ?></td>
                                    <td class="text-end fw-semibold">₱<?php echo e(number_format($value, 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="fw-bold">Total</td>
                                <td class="text-end fw-bold text-primary"><?php echo e(number_format($totalLiters, 3)); ?> L</td>
                                <td></td>
                                <td class="text-end fw-bold">₱<?php echo e(number_format($totalGross, 2)); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="section-heading">Discounts</div>
                <?php if($allDisc->isEmpty()): ?>
                    <p class="text-muted mb-0">No discounts recorded for this shift.</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Customer</th>
                                <th>Fuel</th>
                                <th class="text-end">Liters</th>
                                <th class="text-end">Retail Price</th>
                                <th class="text-end">Discount/L</th>
                                <th class="text-end">Discount Total</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $allDisc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($d->customer->First_name ?? '—'); ?> <?php echo e($d->customer->Last_name ?? ''); ?></td>
                                    <td><?php echo e($d->fuel->fuel_name ?? '—'); ?></td>
                                    <td class="text-end"><?php echo e(number_format($d->liters, 3)); ?></td>
                                    <td class="text-end">₱<?php echo e(number_format($d->retail_price, 2)); ?></td>
                                    <td class="text-end">₱<?php echo e(number_format($d->discount_per_liter, 2)); ?></td>
                                    <td class="text-end fw-semibold text-warning">₱<?php echo e(number_format($d->discount_sale, 2)); ?></td>
                                    <td class="text-muted"><?php echo e($d->description ?? '—'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="5" class="fw-bold">Total Discount</td>
                                <td class="text-end fw-bold text-warning">₱<?php echo e(number_format($totalDisc, 2)); ?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="section-heading">Credits</div>
                <?php if($allCred->isEmpty()): ?>
                    <p class="text-muted mb-0">No credits recorded for this shift.</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Customer</th>
                                <th class="text-end">Liters</th>
                                <th class="text-end">Retail Price</th>
                                <th class="text-end">Retail Sale</th>
                                <th class="text-center">Discounted</th>
                                <th class="text-end">Discounted Sale</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $allCred; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($c->customer->First_name ?? '—'); ?> <?php echo e($c->customer->Last_name ?? ''); ?></td>
                                    <td class="text-end"><?php echo e(number_format($c->liters, 3)); ?></td>
                                    <td class="text-end">₱<?php echo e(number_format($c->retail_price, 2)); ?></td>
                                    <td class="text-end">₱<?php echo e(number_format($c->retail_sale, 2)); ?></td>
                                    <td class="text-center">
                                        <?php if($c->discounted): ?>
                                            <span class="badge bg-success">Yes</span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted">No</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php echo e($c->discounted ? '₱' . number_format($c->discounted_sale, 2) : '—'); ?>

                                    </td>
                                    <td class="text-muted"><?php echo e($c->description ?? '—'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="fw-bold">Total Credit</td>
                                <td class="text-end fw-bold text-danger">₱<?php echo e(number_format($totalCredit, 2)); ?></td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\john mark bolanon\laravel\Seal-gasolineStation\resources\views/Shift/show.blade.php ENDPATH**/ ?>