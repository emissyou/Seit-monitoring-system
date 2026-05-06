
<style>
    :root {
        --red: #ef4444;
        --red-light: #fee2e2;
        --red-dark: #dc2626;
        --green: #10b981;
        --green-light: #d1fae5;
        --amber: #f59e0b;
        --amber-light: #fef3c7;
        --blue: #3b82f6;
        --blue-light: #eff6ff;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-700: #374151;
        --gray-900: #111827;
    }

    .shift-page * { box-sizing: border-box; font-family: 'Geist', 'DM Sans', system-ui, sans-serif; }

    /* ── Page header ── */
    .shift-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 24px;
    }
    .shift-page-header .breadcrumb-label {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--gray-400);
        margin-bottom: 2px;
    }
    .shift-page-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0 0 2px;
    }
    .shift-page-header p {
        font-size: 13px;
        color: var(--gray-500);
        margin: 0;
    }

    /* ── Shift status banner ── */
    .shift-status-banner {
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: 14px;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }
    .shift-status-banner.is-open { border-left: 4px solid var(--green); }
    .shift-status-banner.is-closed { border-left: 4px solid var(--gray-200); }

    .shift-status-banner .pulse-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .shift-status-banner .pulse-dot.active {
        background: var(--green);
        box-shadow: 0 0 0 3px rgba(16,185,129,.2);
        animation: pulse-ring 1.8s ease-out infinite;
    }
    .shift-status-banner .pulse-dot.inactive { background: var(--gray-400); }
    @keyframes pulse-ring {
        0%   { box-shadow: 0 0 0 0 rgba(16,185,129,.4); }
        70%  { box-shadow: 0 0 0 8px rgba(16,185,129,0); }
        100% { box-shadow: 0 0 0 0 rgba(16,185,129,0); }
    }

    .shift-status-info { display: flex; align-items: center; gap: 12px; }
    .shift-status-label { font-size: 16px; font-weight: 700; color: var(--gray-900); margin-bottom: 2px; }
    .shift-status-label .open-text { color: var(--green); }
    .shift-status-sub { font-size: 12px; color: var(--gray-500); }

    .btn-shift-open {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 22px; border-radius: 8px; font-size: 14px; font-weight: 600;
        background: var(--green); color: #fff; border: none; text-decoration: none;
        transition: background .15s;
    }
    .btn-shift-open:hover { background: #059669; color: #fff; }
    .btn-shift-close {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 22px; border-radius: 8px; font-size: 14px; font-weight: 600;
        background: var(--red); color: #fff; border: none; text-decoration: none;
        transition: background .15s;
    }
    .btn-shift-close:hover { background: var(--red-dark); color: #fff; }

    /* ── KPI cards ── */
    .sh-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }
    @media (max-width: 900px) { .sh-kpi-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 540px) { .sh-kpi-grid { grid-template-columns: 1fr; } }

    .sh-kpi-card {
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: 14px;
        padding: 20px;
        position: relative;
        overflow: hidden;
    }
    .sh-kpi-card .sh-kpi-icon {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 17px; margin-bottom: 14px;
    }
    .sh-kpi-card .sh-kpi-label {
        font-size: 10px; font-weight: 600; letter-spacing: .08em;
        text-transform: uppercase; color: var(--gray-400); margin-bottom: 4px;
    }
    .sh-kpi-card .sh-kpi-value {
        font-size: 22px; font-weight: 800; color: var(--gray-900); line-height: 1;
        margin-bottom: 6px;
    }
    .sh-kpi-card .sh-kpi-sub { font-size: 12px; color: var(--gray-400); }
    .sh-kpi-card .sh-kpi-blob {
        position: absolute; right: -20px; top: -20px;
        width: 80px; height: 80px; border-radius: 50%; opacity: .15;
    }

    /* ── Section title ── */
    .sh-section-heading {
        font-size: 14px; font-weight: 700; color: var(--gray-700);
        letter-spacing: .01em; margin-bottom: 12px;
        display: flex; align-items: center; gap: 8px;
    }
    .sh-section-heading::after {
        content: ''; flex: 1; height: 1px; background: var(--gray-200);
    }

    /* ── Fuel cards ── */
    .fuel-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 20px; }
    @media (max-width: 700px) { .fuel-grid { grid-template-columns: 1fr; } }

    .fuel-card {
        background: #fff; border: 1px solid var(--gray-200);
        border-radius: 14px; padding: 18px;
    }
    .fuel-card .fuel-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;
        margin-bottom: 12px;
    }
    .fuel-card .fuel-pill .dot { width: 7px; height: 7px; border-radius: 50%; }
    .fuel-card .fuel-value { font-size: 22px; font-weight: 800; color: var(--gray-900); }
    .fuel-card .fuel-liters { font-size: 12px; color: var(--gray-400); margin-bottom: 12px; }
    .fuel-bar { height: 5px; border-radius: 3px; background: var(--gray-100); overflow: hidden; }
    .fuel-bar-fill { height: 100%; border-radius: 3px; }

    /* ── Table card ── */
    .table-card {
        background: #fff; border: 1px solid var(--gray-200); border-radius: 14px;
        /* overflow: hidden removed — was clipping Bootstrap dropdowns */
    }
    .table-card-header {
        padding: 18px 22px 0;
        display: flex; align-items: center; justify-content: space-between;
    }
    .table-card-header h2 { font-size: 16px; font-weight: 700; color: var(--gray-900); margin: 0; }

    /* ── Filter row ── */
    .filter-row {
        display: flex; gap: 10px; align-items: flex-end; flex-wrap: wrap;
        padding: 16px 22px;
        border-bottom: 1px solid var(--gray-100);
    }
    .filter-field label { display: block; font-size: 11px; font-weight: 600; color: var(--gray-500); margin-bottom: 4px; text-transform: uppercase; letter-spacing: .04em; }
    .filter-field input,
    .filter-field select {
        border: 1px solid var(--gray-200); border-radius: 8px;
        padding: 7px 12px; font-size: 13px; color: var(--gray-900);
        background: var(--gray-50); outline: none; height: 36px;
    }
    .filter-field input:focus,
    .filter-field select:focus { border-color: var(--red); box-shadow: 0 0 0 3px rgba(239,68,68,.1); }

    .btn-apply-filter {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 0 18px; height: 36px; border-radius: 8px;
        background: var(--red); color: #fff; font-size: 13px; font-weight: 600;
        border: none; cursor: pointer; transition: background .15s;
    }
    .btn-apply-filter:hover { background: var(--red-dark); }

    /* ── Data table ── */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead tr { border-bottom: 1px solid var(--gray-100); }
    .data-table thead th {
        padding: 10px 14px; font-size: 10px; font-weight: 700;
        letter-spacing: .07em; text-transform: uppercase; color: var(--gray-400);
        white-space: nowrap;
    }
    .data-table tbody tr { border-bottom: 1px solid var(--gray-50); transition: background .1s; }
    .data-table tbody tr:last-child { border-bottom: none; }
    .data-table tbody tr:hover { background: var(--gray-50); }
    .data-table tbody td { padding: 12px 14px; font-size: 13px; color: var(--gray-700); }

    .status-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;
    }
    .status-pill.open { background: var(--green-light); color: #065f46; }
    .status-pill.closed { background: var(--gray-100); color: var(--gray-500); }
    .status-pill .dot { width: 6px; height: 6px; border-radius: 50%; }
    .status-pill.open .dot { background: var(--green); }
    .status-pill.closed .dot { background: var(--gray-400); }

    .action-btn {
        width: 30px; height: 30px; border-radius: 7px;
        border: 1px solid var(--gray-200); background: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer; color: var(--gray-500); font-size: 15px;
        transition: all .15s;
    }
    .action-btn:hover { border-color: var(--gray-400); color: var(--gray-900); background: var(--gray-50); }

    .empty-state { padding: 48px 22px; text-align: center; color: var(--gray-400); font-size: 13px; }
    .empty-state i { font-size: 28px; display: block; margin-bottom: 10px; }

    .pagination-wrap { padding: 14px 22px; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; }
</style>

<div class="shift-page">

    
    <div class="shift-page-header">
        <div>
            <div class="breadcrumb-label">Operations • Shift</div>
            <h1>Shift Management</h1>
            <p>Track daily fuel shift openings, closings, and performance.</p>
        </div>
    </div>

    
    <div class="shift-status-banner <?php echo e($activeShift ? 'is-open' : 'is-closed'); ?>">
        <div class="shift-status-info">
            <div class="pulse-dot <?php echo e($activeShift ? 'active' : 'inactive'); ?>"></div>
            <div>
                <div class="shift-status-label">
                    <?php if($activeShift): ?>
                        Shift is <span class="open-text">OPEN</span>
                    <?php else: ?>
                        No Active Shift
                    <?php endif; ?>
                </div>
                <div class="shift-status-sub">
                    <?php if($activeShift): ?>
                        Opened: <?php echo e($activeShift->opened_at?->format('M d, Y • h:i A')); ?>

                    <?php elseif($latestClosedShift): ?>
                        Last closed: <?php echo e($latestClosedShift->closed_at?->format('M d, Y • h:i A')); ?>

                    <?php else: ?>
                        No shifts recorded yet
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div>
            <?php if($activeShift): ?>
                <a href="<?php echo e(route('shift.management', ['view' => 'close'])); ?>" class="btn-shift-close">
                    <i class="bi bi-stop-circle"></i> Close Shift
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('shift.management', ['view' => 'open'])); ?>" class="btn-shift-open">
                    <i class="bi bi-play-circle"></i> Open Shift
                </a>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="sh-kpi-grid">
        <div class="sh-kpi-card">
            <div class="sh-kpi-blob" style="background: var(--blue);"></div>
            <div class="sh-kpi-icon" style="background: var(--blue-light); color: var(--blue);">
                <i class="bi bi-droplet-fill"></i>
            </div>
            <div class="sh-kpi-label">Total Liters</div>
            <div class="sh-kpi-value"><?php echo e(number_format($totals['liters'] ?? 0, 3)); ?><small style="font-size:14px;font-weight:500;color:var(--gray-400);"> L</small></div>
            <div class="sh-kpi-sub">dispensed this period</div>
        </div>
        <div class="sh-kpi-card">
            <div class="sh-kpi-blob" style="background: var(--gray-700);"></div>
            <div class="sh-kpi-icon" style="background: var(--gray-100); color: var(--gray-700);">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="sh-kpi-label">Gross Sales</div>
            <div class="sh-kpi-value" style="font-size:18px;">₱<?php echo e(number_format($totals['gross'] ?? 0, 2)); ?></div>
            <div class="sh-kpi-sub">before discounts</div>
        </div>
        <div class="sh-kpi-card">
            <div class="sh-kpi-blob" style="background: var(--green);"></div>
            <div class="sh-kpi-icon" style="background: var(--green-light); color: var(--green);">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div class="sh-kpi-label">Net Sales</div>
            <div class="sh-kpi-value" style="font-size:18px; color: var(--green);">₱<?php echo e(number_format($totals['net'] ?? 0, 2)); ?></div>
            <div class="sh-kpi-sub">after discounts & credits</div>
        </div>
        <div class="sh-kpi-card">
            <div class="sh-kpi-blob" style="background: var(--amber);"></div>
            <div class="sh-kpi-icon" style="background: var(--amber-light); color: var(--amber);">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="sh-kpi-label">Cash in Hand</div>
            <div class="sh-kpi-value" style="font-size:18px;">₱<?php echo e(number_format($totals['cash_in_hand'] ?? 0, 2)); ?></div>
            <div class="sh-kpi-sub">collected cash</div>
        </div>
    </div>

    
    <div class="sh-section-heading">Fuel Performance</div>
    <?php
        $fuelColors = [
            'Premium' => ['bg' => '#fef3c7', 'text' => '#92400e', 'dot' => '#f59e0b', 'bar' => '#f59e0b'],
            'Regular' => ['bg' => '#d1fae5', 'text' => '#065f46', 'dot' => '#10b981', 'bar' => '#10b981'],
            'Diesel'  => ['bg' => '#fee2e2', 'text' => '#991b1b', 'dot' => '#ef4444', 'bar' => '#ef4444'],
        ];
        $maxValue = collect($fuelTotals)->max('value') ?: 1;
    ?>
    <div class="fuel-grid">
        <?php $__currentLoopData = $fuelTotals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fuelName => $fuel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $c = $fuelColors[$fuelName] ?? ['bg'=>'#e0e7ff','text'=>'#4338ca','dot'=>'#6366f1','bar'=>'#6366f1']; ?>
            <div class="fuel-card">
                <div class="fuel-pill" style="background:<?php echo e($c['bg']); ?>; color:<?php echo e($c['text']); ?>;">
                    <span class="dot" style="background:<?php echo e($c['dot']); ?>;"></span>
                    <?php echo e($fuelName); ?>

                </div>
                <div class="fuel-value">₱<?php echo e(number_format($fuel['value'] ?? 0, 2)); ?></div>
                <div class="fuel-liters"><?php echo e(number_format($fuel['liters'] ?? 0, 3)); ?> L dispensed</div>
                <div class="fuel-bar">
                    <div class="fuel-bar-fill" style="background:<?php echo e($c['bar']); ?>; width: <?php echo e($maxValue > 0 ? round(($fuel['value'] ?? 0) / $maxValue * 100) : 0); ?>%;"></div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="sh-section-heading">Shift History</div>
    <div class="table-card">
        <div class="table-card-header">
            <h2>All Shifts</h2>
        </div>

        
        <form method="GET">
            <input type="hidden" name="view" value="home">
            <div class="filter-row">
                <div class="filter-field">
                    <label>From</label>
                    <input type="date" name="date_from" value="<?php echo e($dateFrom); ?>">
                </div>
                <div class="filter-field">
                    <label>To</label>
                    <input type="date" name="date_to" value="<?php echo e($dateTo); ?>">
                </div>
                <div class="filter-field">
                    <label>Status</label>
                    <select name="status">
                        <option value="all" <?php if($statusFilter === 'all'): echo 'selected'; endif; ?>>All Status</option>
                        <option value="open" <?php if($statusFilter === 'open'): echo 'selected'; endif; ?>>Open</option>
                        <option value="closed" <?php if($statusFilter === 'closed'): echo 'selected'; endif; ?>>Closed</option>
                    </select>
                </div>
                <button type="submit" class="btn-apply-filter">
                    <i class="bi bi-funnel"></i> Apply Filter
                </button>
            </div>
        </form>

        
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
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
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td style="font-weight:600; color:var(--gray-900);"><?php echo e($shift->sales_date->format('M d, Y')); ?></td>
                            <td>
                                <span class="status-pill <?php echo e($shift->status === 'open' ? 'open' : 'closed'); ?>">
                                    <span class="dot"></span>
                                    <?php echo e(ucfirst($shift->status)); ?>

                                </span>
                            </td>
                            <td><?php echo e(number_format($shift->db_liters, 3)); ?> L</td>
                            <td>₱<?php echo e(number_format($shift->db_gross, 2)); ?></td>
                            <td style="color:var(--red);">₱<?php echo e(number_format($shift->db_discount, 2)); ?></td>
                            <td>₱<?php echo e(number_format($shift->db_credit, 2)); ?></td>
                            <td style="font-weight:700; color:var(--gray-900);">₱<?php echo e(number_format($shift->db_net, 2)); ?></td>
                            <td>₱<?php echo e(number_format($shift->db_cash_in_hand, 2)); ?></td>
                            <td style="color:var(--gray-400); font-size:12px;"><?php echo e($shift->closed_at?->format('M d, h:i A') ?? '—'); ?></td>
                            <td style="text-align:right;">
                                <div class="dropdown">
                                    <button class="action-btn" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" style="font-size:13px; border-radius:10px; border-color:var(--gray-200); box-shadow:0 8px 24px rgba(0,0,0,.08);">
                                        <li>
                                            <a class="dropdown-item" href="<?php echo e(route('shift.view', $shift->ShiftID)); ?>">
                                                <i class="bi bi-eye me-2"></i>View
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="<?php echo e(route('shift.edit', $shift->ShiftID)); ?>">
                                                <i class="bi bi-pencil me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="archiveShift(<?php echo e($shift->ShiftID); ?>); return false;" style="color:var(--amber);">
                                                <i class="bi bi-archive me-2"></i>Archive
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <i class="bi bi-calendar-x"></i>
                                    No shifts found for the selected filters.
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="pagination-wrap">
            <?php echo e($shifts->appends(request()->query())->links('pagination::bootstrap-5')); ?>

        </div>
    </div>
</div><?php /**PATH C:\Users\john mark bolanon\laravel\Seal-gasolineStation\resources\views/Shift/partials/home.blade.php ENDPATH**/ ?>