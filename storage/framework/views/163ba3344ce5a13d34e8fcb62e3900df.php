
<div class="row g-4">

    
    <div class="col-12">
        <div class="card shadow-sm border-<?php echo e($activeShift ? 'success' : 'secondary'); ?>">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="status-dot rounded-circle <?php echo e($activeShift ? 'bg-success' : 'bg-secondary'); ?>"
                         style="width: 14px; height: 14px;"></div>
                    <div>
                        <h5 class="mb-1 fw-bold">
                            <?php if($activeShift): ?>
                                Shift is <span class="text-success">OPEN</span>
                            <?php else: ?>
                                No Active Shift
                            <?php endif; ?>
                        </h5>
                        <small class="text-muted">
                            <?php if($activeShift): ?>
                                Opened: <?php echo e($activeShift->opened_at?->format('M d, Y • h:i A')); ?>

                            <?php elseif($latestClosedShift): ?>
                                Last closed: <?php echo e($latestClosedShift->closed_at?->format('M d, Y • h:i A')); ?>

                            <?php else: ?>
                                No shifts yet
                            <?php endif; ?>
                        </small>
                    </div>
                </div>

                <div>
                    <?php if($activeShift): ?>
                        <a href="<?php echo e(route('shift.management', ['view' => 'close'])); ?>" class="btn btn-danger btn-lg">
                            <i class="bi bi-stop-circle me-2"></i>Close Shift
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('shift.management', ['view' => 'open'])); ?>" class="btn btn-success btn-lg">
                            <i class="bi bi-play-circle me-2"></i>Open Shift
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase">Total Liters</div>
                <div class="stat-value text-primary"><?php echo e(number_format($totals['liters'] ?? 0, 3)); ?> <small>L</small></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase">Gross Sales</div>
                <div class="stat-value">₱<?php echo e(number_format($totals['gross'] ?? 0, 2)); ?></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase">Net Sales</div>
                <div class="stat-value text-success">₱<?php echo e(number_format($totals['net'] ?? 0, 2)); ?></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase">Cash in Hand</div>
                <div class="stat-value">₱<?php echo e(number_format($totals['cash_in_hand'] ?? 0, 2)); ?></div>
            </div>
        </div>
    </div>

    
    <div class="col-12">
        <h5 class="section-title mb-3">Fuel Performance</h5>
        <div class="row g-3">
            <?php
                $fuelColors = [
                    'Premium' => ['bg' => '#fef3c7', 'text' => '#92400e', 'bar' => '#f59e0b'],
                    'Regular' => ['bg' => '#d1fae5', 'text' => '#065f46', 'bar' => '#10b981'],
                    'Diesel'  => ['bg' => '#fee2e2', 'text' => '#991b1b', 'bar' => '#ef4444'],
                ];
            ?>
            <?php $__currentLoopData = $fuelTotals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fuelName => $fuel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $c = $fuelColors[$fuelName] ?? ['bg'=>'#e0e7ff','text'=>'#4338ca','bar'=>'#6366f1']; ?>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="fuel-badge px-3 py-1" style="background:<?php echo e($c['bg']); ?>; color:<?php echo e($c['text']); ?>;">
                                    <?php echo e($fuelName); ?>

                                </span>
                                <span class="text-muted small"><?php echo e(number_format($fuel['liters'] ?? 0, 3)); ?> L</span>
                            </div>
                            <div class="mt-3 fw-bold fs-4">₱<?php echo e(number_format($fuel['value'] ?? 0, 2)); ?></div>
                            <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar" style="background:<?php echo e($c['bar']); ?>; width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Shift History</h5>

                
                <form method="GET" class="row g-3 mb-4">
                    <input type="hidden" name="view" value="home">
                    <div class="col-md-2">
                        <label class="form-label small">From</label>
                        <input type="date" name="date_from" value="<?php echo e($dateFrom); ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">To</label>
                        <input type="date" name="date_to" value="<?php echo e($dateTo); ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="all" <?php if($statusFilter === 'all'): echo 'selected'; endif; ?>>All</option>
                            <option value="open" <?php if($statusFilter === 'open'): echo 'selected'; endif; ?>>Open</option>
                            <option value="closed" <?php if($statusFilter === 'closed'): echo 'selected'; endif; ?>>Closed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Archived</label>
                        <select name="archived" class="form-select form-select-sm">
                            <option value="false" <?php if($archivedFilter === 'false'): echo 'selected'; endif; ?>>Active</option>
                            <option value="true" <?php if($archivedFilter === 'true'): echo 'selected'; endif; ?>>Archived</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                    </div>
                </form>

                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
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
                            <?php $__empty_1 = true; $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($shift->sales_date->format('M d, Y')); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($shift->status === 'open' ? 'success' : 'secondary'); ?>">
                                            <?php echo e(ucfirst($shift->status)); ?>

                                        </span>
                                    </td>
                                    
                                    <td><?php echo e(number_format($shift->db_liters, 3)); ?> L</td>
                                    <td>₱<?php echo e(number_format($shift->db_gross, 2)); ?></td>
                                    <td>₱<?php echo e(number_format($shift->db_discount, 2)); ?></td>
                                    <td>₱<?php echo e(number_format($shift->db_credit, 2)); ?></td>
                                    <td class="fw-semibold">₱<?php echo e(number_format($shift->db_net, 2)); ?></td>
                                    <td>₱<?php echo e(number_format($shift->db_cash_in_hand, 2)); ?></td>
                                    <td class="text-muted small"><?php echo e($shift->closed_at?->format('M d, h:i A') ?? '—'); ?></td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <?php if($shift->archived ?? false): ?>
                                                    <li><a class="dropdown-item" href="#" onclick="restoreShift(<?php echo e($shift->ShiftID); ?>)">Restore</a></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteShift(<?php echo e($shift->ShiftID); ?>)">Delete</a></li>
                                                <?php else: ?>
                                                    <li><a class="dropdown-item" href="<?php echo e(route('shift.view', $shift->ShiftID)); ?>">View</a></li>
                                                    <li><a class="dropdown-item" href="<?php echo e(route('shift.edit', $shift->ShiftID)); ?>">Edit</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-warning" href="#" onclick="archiveShift(<?php echo e($shift->ShiftID); ?>)">Archive</a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="10" class="text-center py-5 text-muted">No shifts found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="d-flex justify-content-end mt-3">
                    <?php echo e($shifts->appends(request()->query())->links()); ?>

                </div>

            </div>
        </div>
    </div>
</div><?php /**PATH C:\Users\john mark bolanon\laravel\Seal-gasolineStation\resources\views/Shift/partials/home.blade.php ENDPATH**/ ?>