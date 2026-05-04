
<div class="row g-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="card-title mb-1">Archived Shifts</h5>
                        <p class="text-muted small mb-0">Archived shifts are hidden from the dashboard. You can restore or permanently delete them here.</p>
                    </div>
                    <span class="badge bg-secondary fs-6"><?php echo e($archivedShifts->total()); ?> total</span>
                </div>

                
                <form method="GET" class="row g-3 mb-4">
                    <input type="hidden" name="view" value="archive">
                    <div class="col-md-2">
                        <label class="form-label small">From</label>
                        <input type="date" name="archive_from" value="<?php echo e(request('archive_from')); ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">To</label>
                        <input type="date" name="archive_to" value="<?php echo e(request('archive_to')); ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    <?php if(request('archive_from') || request('archive_to')): ?>
                        <div class="col-md-2 d-flex align-items-end">
                            <a href="<?php echo e(route('shift.management', ['view' => 'archive'])); ?>" class="btn btn-outline-secondary w-100">Clear</a>
                        </div>
                    <?php endif; ?>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Liters</th>
                                <th>Gross</th>
                                <th>Discount</th>
                                <th>Credit</th>
                                <th>Net</th>
                                <th>Cash</th>
                                <th>Closed At</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $archivedShifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="table-warning">
                                    <td><?php echo e($shift->sales_date->format('M d, Y')); ?></td>
                                    <td><?php echo e(number_format($shift->db_liters, 3)); ?> L</td>
                                    <td>₱<?php echo e(number_format($shift->db_gross, 2)); ?></td>
                                    <td>₱<?php echo e(number_format($shift->db_discount, 2)); ?></td>
                                    <td>₱<?php echo e(number_format($shift->db_credit, 2)); ?></td>
                                    <td class="fw-semibold">₱<?php echo e(number_format($shift->db_net, 2)); ?></td>
                                    <td>₱<?php echo e(number_format($shift->db_cash_in_hand, 2)); ?></td>
                                    <td class="text-muted small"><?php echo e($shift->closed_at?->format('M d, h:i A') ?? '—'); ?></td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="<?php echo e(route('shift.view', $shift->ShiftID)); ?>"
                                               class="btn btn-sm btn-outline-primary"
                                               title="View shift details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-success"
                                                    onclick="restoreShift(<?php echo e($shift->ShiftID); ?>)"
                                                    title="Restore to dashboard">
                                                <i class="bi bi-arrow-counterclockwise"></i> Restore
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="deleteShift(<?php echo e($shift->ShiftID); ?>)"
                                                    title="Permanently delete">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="bi bi-archive fs-2 d-block mb-2"></i>
                                        No archived shifts found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="d-flex justify-content-end mt-3">
                    <?php echo e($archivedShifts->appends(request()->query())->links('pagination::bootstrap-5')); ?>

                </div>

            </div>
        </div>
    </div>
</div><?php /**PATH C:\Users\john mark bolanon\laravel\Seal-gasolineStation\resources\views/Shift/partials/archive.blade.php ENDPATH**/ ?>