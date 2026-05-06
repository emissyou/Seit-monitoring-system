

<?php $__env->startSection('title', 'Totalizer Log'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
/* ─── Design Tokens ─────────────────────────────────────── */
:root {
    --bg:          #f0f2f7;
    --surface:     #ffffff;
    --surface-2:   #f7f8fc;
    --border:      #e8eaf2;
    --border-soft: #f0f2f8;

    --ink-1:  #0d1117;
    --ink-2:  #3d4555;
    --ink-3:  #7c84a0;
    --ink-4:  #adb5cc;

    --red:          #e8304a;
    --red-bg:       #fef0f2;
    --red-border:   #fbd0d7;

    --green:        #00b37e;
    --green-bg:     #edfaf5;
    --green-border: #b3eed9;

    --amber:        #f59e0b;
    --amber-bg:     #fffbeb;
    --amber-border: #fde68a;

    --blue:         #3b6ef6;
    --blue-bg:      #eef2fe;
    --blue-border:  #c7d6fc;

    --radius:    14px;
    --radius-sm: 8px;

    --shadow-card:  0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    --shadow-hover: 0 8px 28px rgba(0,0,0,0.10);

    --font-body: 'Sora', sans-serif;
    --font-mono: 'JetBrains Mono', monospace;
}

/* ─── Page Header ────────────────────────────────────────── */
.cl-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1.5rem 1.75rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-card);
    position: relative;
    overflow: hidden;
    font-family: var(--font-body);
}
.cl-page-header::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 4px; height: 100%;
    background: linear-gradient(180deg, var(--red) 0%, #ff6b81 100%);
    border-radius: 4px 0 0 4px;
}
.cl-eyebrow {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--ink-3);
    margin-bottom: 0.3rem;
    font-family: var(--font-body);
}
.cl-page-title {
    font-size: 1.45rem;
    font-weight: 800;
    color: var(--ink-1);
    margin: 0 0 0.15rem;
    line-height: 1.2;
    font-family: var(--font-body);
}
.cl-page-sub {
    font-size: 0.8rem;
    color: var(--ink-3);
    margin: 0;
    font-family: var(--font-body);
}
.btn-export {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.3rem;
    font-size: 0.8rem;
    font-weight: 600;
    font-family: var(--font-body);
    background: var(--ink-1);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    text-decoration: none;
    letter-spacing: 0.3px;
    transition: background 0.2s, transform 0.15s;
    white-space: nowrap;
}
.btn-export:hover { background: #1e293b; color: #fff; transform: translateY(-1px); }

/* ─── Summary Grid ───────────────────────────────────────── */
.cl-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 1100px) { .cl-summary-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 560px)  { .cl-summary-grid { grid-template-columns: 1fr; } }

.cl-stat {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1.35rem 1.5rem;
    box-shadow: var(--shadow-card);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    position: relative;
    overflow: hidden;
    font-family: var(--font-body);
}
.cl-stat:hover { transform: translateY(-3px); box-shadow: var(--shadow-hover); }

.cl-stat-glow {
    position: absolute;
    top: -20px; right: -20px;
    width: 90px; height: 90px;
    border-radius: 50%;
    opacity: 0.08;
    pointer-events: none;
}
.cl-stat-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    margin-bottom: 1rem;
}
.cl-stat-label {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    color: var(--ink-3);
    margin-bottom: 0.35rem;
}
.cl-stat-value {
    font-size: 1.55rem;
    font-weight: 800;
    color: var(--ink-1);
    line-height: 1;
    margin-bottom: 0.4rem;
    font-variant-numeric: tabular-nums;
    font-family: var(--font-mono);
}
.cl-stat-sub { font-size: 0.75rem; color: var(--ink-4); font-weight: 500; }

/* ─── Filter Bar ─────────────────────────────────────────── */
.cl-filter-bar {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1.15rem 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-card);
    font-family: var(--font-body);
}
.cl-filter-label {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--ink-3);
    margin-bottom: 0.4rem;
    display: block;
    font-family: var(--font-body);
}
.cl-filter-bar .form-select,
.cl-filter-bar .form-control {
    font-family: var(--font-body);
    font-size: 0.82rem;
    font-weight: 500;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    background: var(--surface-2);
    color: var(--ink-1);
    padding: 0.5rem 0.85rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.cl-filter-bar .form-select:focus,
.cl-filter-bar .form-control:focus {
    outline: none;
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(59,110,246,0.12);
}
.btn-apply {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.55rem 1.25rem;
    font-size: 0.8rem;
    font-weight: 600;
    font-family: var(--font-body);
    background: var(--red);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: background 0.2s, transform 0.15s;
    letter-spacing: 0.2px;
    white-space: nowrap;
}
.btn-apply:hover { background: #c8243d; transform: translateY(-1px); }

.btn-reset {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px; height: 36px;
    font-size: 0.9rem;
    background: var(--surface-2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--ink-3);
    text-decoration: none;
    transition: all 0.2s;
    flex-shrink: 0;
}
.btn-reset:hover { background: var(--red-bg); border-color: var(--red-border); color: var(--red); }

/* ─── Alert ──────────────────────────────────────────────── */
.cl-alert-success {
    display: flex; align-items: center; gap: 0.65rem;
    padding: 0.85rem 1.15rem;
    border-radius: var(--radius-sm);
    font-size: 0.83rem;
    margin-bottom: 1.25rem;
    background: var(--green-bg);
    border: 1px solid var(--green-border);
    color: #065f46;
    font-family: var(--font-body);
}

/* ─── Table Card ─────────────────────────────────────────── */
.cl-table-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow-card);
    font-family: var(--font-body);
}
.cl-table-card table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
}
.cl-table-card thead th {
    background: var(--surface-2);
    border-bottom: 1.5px solid var(--border);
    padding: 0.85rem 1rem;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    color: var(--ink-3);
    white-space: nowrap;
    font-family: var(--font-body);
}
.cl-table-card tbody td {
    padding: 0.9rem 1rem;
    border-bottom: 1px solid var(--border-soft);
    vertical-align: middle;
    color: var(--ink-2);
    font-family: var(--font-body);
}
.cl-table-card tbody tr:last-child td { border-bottom: none; }
.cl-table-card tbody tr { transition: background 0.15s; }
.cl-table-card tbody tr:hover { background: #f7f8fc; }

/* ─── Fuel Badge ─────────────────────────────────────────── */
.cl-fuel {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.28rem 0.7rem;
    background: var(--red-bg);
    border: 1px solid var(--red-border);
    border-radius: 6px;
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--red);
    letter-spacing: 0.3px;
    white-space: nowrap;
    font-family: var(--font-body);
}

/* ─── Pump Badge ─────────────────────────────────────────── */
.cl-pump {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.28rem 0.7rem;
    background: var(--blue-bg);
    border: 1px solid var(--blue-border);
    border-radius: 6px;
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--blue);
    letter-spacing: 0.3px;
    white-space: nowrap;
    font-family: var(--font-body);
}

/* ─── Mono values ────────────────────────────────────────── */
.cl-mono      { font-family: var(--font-mono); font-size: 0.82rem; font-weight: 500; }
.cl-mono-bold { font-family: var(--font-mono); font-size: 0.85rem; font-weight: 600; color: var(--ink-1); }

/* ─── Date cell ──────────────────────────────────────────── */
.cl-date-main { font-weight: 600; color: var(--ink-1); font-size: 0.85rem; font-family: var(--font-body); }
.cl-date-sub  { font-size: 0.72rem; color: var(--ink-4); font-family: var(--font-body); margin-top: 2px; }

/* ─── Empty State ────────────────────────────────────────── */
.cl-empty { padding: 4.5rem 1rem; text-align: center; color: var(--ink-4); }
.cl-empty-icon { font-size: 3rem; opacity: 0.2; margin-bottom: 1rem; display: block; }
.cl-empty h5 { font-size: 1rem; font-weight: 700; color: var(--ink-3); margin-bottom: 0.4rem; font-family: var(--font-body); }
.cl-empty p  { font-size: 0.83rem; margin: 0; }

/* ─── Pagination ─────────────────────────────────────────── */
.cl-pagination {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border-soft);
    background: var(--surface-2);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.cl-page-info { font-size: 0.75rem; color: var(--ink-3); font-family: var(--font-body); }

/* ─── Animations ─────────────────────────────────────────── */
@keyframes cl-fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}
.cl-page-header       { animation: cl-fadeUp 0.35s ease both; }
.cl-stat              { animation: cl-fadeUp 0.4s ease both; }
.cl-stat:nth-child(1) { animation-delay: 0.05s; }
.cl-stat:nth-child(2) { animation-delay: 0.10s; }
.cl-stat:nth-child(3) { animation-delay: 0.15s; }
.cl-stat:nth-child(4) { animation-delay: 0.20s; }
.cl-filter-bar        { animation: cl-fadeUp 0.4s 0.22s ease both; }
.cl-table-card        { animation: cl-fadeUp 0.4s 0.28s ease both; }

/* ─── Responsive ─────────────────────────────────────────── */
@media (max-width: 768px) {
    .cl-page-header { padding: 1.1rem 1.25rem; }
    .cl-stat        { padding: 1rem 1.1rem; }
    .cl-table-card thead th,
    .cl-table-card tbody td { padding: 0.75rem 0.75rem; }
}
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>


<div class="cl-page-header">
    <div>
        <div class="cl-eyebrow">Operations &bull; Readings</div>
        <h1 class="cl-page-title">Totalizer Log</h1>
        <p class="cl-page-sub">Closing totalizer readings recorded per shift</p>
    </div>
    <a href="<?php echo e(route('totalizer.export', request()->query())); ?>" class="btn-export">
        <i class="bi bi-download"></i> Export CSV
    </a>
</div>


<?php
    $allLogs       = \App\Models\TotalizerLog::all();
    $totalReadings = $allLogs->count();
    $totalVolume   = $allLogs->sum('reading');
    $uniquePumps   = $allLogs->pluck('pump_name')->unique()->count();
    $latestDate    = $allLogs->max('date_recorded');
?>

<div class="cl-summary-grid">

    <div class="cl-stat">
        <div class="cl-stat-glow" style="background:#e8304a;"></div>
        <div class="cl-stat-icon" style="background:var(--red-bg);color:var(--red);">
            <i class="bi bi-speedometer2"></i>
        </div>
        <div class="cl-stat-label">Total Readings</div>
        <div class="cl-stat-value"><?php echo e(number_format($totalReadings)); ?></div>
        <div class="cl-stat-sub">all recorded entries</div>
    </div>

    <div class="cl-stat">
        <div class="cl-stat-glow" style="background:#00b37e;"></div>
        <div class="cl-stat-icon" style="background:var(--green-bg);color:var(--green);">
            <i class="bi bi-droplet-fill"></i>
        </div>
        <div class="cl-stat-label">Total Volume (L)</div>
        <div class="cl-stat-value"><?php echo e(number_format($totalVolume, 0)); ?></div>
        <div class="cl-stat-sub">cumulative reading</div>
    </div>

    <div class="cl-stat">
        <div class="cl-stat-glow" style="background:#3b6ef6;"></div>
        <div class="cl-stat-icon" style="background:var(--blue-bg);color:var(--blue);">
            <i class="bi bi-fuel-pump-fill"></i>
        </div>
        <div class="cl-stat-label">Active Pumps</div>
        <div class="cl-stat-value"><?php echo e($uniquePumps); ?></div>
        <div class="cl-stat-sub">with recorded readings</div>
    </div>

    <div class="cl-stat">
        <div class="cl-stat-glow" style="background:#f59e0b;"></div>
        <div class="cl-stat-icon" style="background:var(--amber-bg);color:var(--amber);">
            <i class="bi bi-calendar-check-fill"></i>
        </div>
        <div class="cl-stat-label">Latest Reading</div>
        <div class="cl-stat-value" style="font-size:1.1rem;">
            <?php echo e($latestDate ? \Carbon\Carbon::parse($latestDate)->format('M d') : '—'); ?>

        </div>
        <div class="cl-stat-sub">
            <?php echo e($latestDate ? \Carbon\Carbon::parse($latestDate)->format('Y') : 'no records yet'); ?>

        </div>
    </div>

</div>


<?php if(session('success')): ?>
    <div class="cl-alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i>
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:.65rem;margin-left:auto;"></button>
    </div>
<?php endif; ?>


<div class="cl-filter-bar">
    <form method="GET" action="<?php echo e(route('totalizer.index')); ?>" class="row g-3 align-items-end">

        <div class="col-md-3">
            <label class="cl-filter-label">Pump</label>
            <select name="pump_id" class="form-select form-select-sm">
                <option value="">All Pumps</option>
                <?php $__currentLoopData = $pumps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pump): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($pump->PumpID); ?>" <?php echo e(request('pump_id') == $pump->PumpID ? 'selected' : ''); ?>>
                        <?php echo e($pump->pump_name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="col-md-3">
            <label class="cl-filter-label">Fuel</label>
            <select name="fuel_id" class="form-select form-select-sm">
                <option value="">All Fuels</option>
                <?php $__currentLoopData = $fuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fuel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($fuel->FuelID); ?>" <?php echo e(request('fuel_id') == $fuel->FuelID ? 'selected' : ''); ?>>
                        <?php echo e($fuel->fuel_name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="col-md-2">
            <label class="cl-filter-label">Date From</label>
            <input type="date" name="date_from" class="form-control form-control-sm" value="<?php echo e(request('date_from')); ?>">
        </div>

        <div class="col-md-2">
            <label class="cl-filter-label">Date To</label>
            <input type="date" name="date_to" class="form-control form-control-sm" value="<?php echo e(request('date_to')); ?>">
        </div>

        <div class="col-md-2 d-flex gap-2 align-items-end">
            <button type="submit" class="btn-apply flex-grow-1">
                <i class="bi bi-funnel-fill"></i> Filter
            </button>
            <a href="<?php echo e(route('totalizer.index')); ?>" class="btn-reset" title="Clear filters">
                <i class="bi bi-x-lg"></i>
            </a>
        </div>

    </form>
</div>


<div class="cl-table-card">
    <div class="table-responsive">
        <table>
            <thead>
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
                                <div class="cl-date-main">
                                    <?php echo e(date('M d, Y', strtotime($log->date_recorded))); ?>

                                </div>
                                <?php if(!empty($log->closed_at)): ?>
                                    <div class="cl-date-sub">
                                        <i class="bi bi-clock" style="font-size:0.65rem;"></i>
                                        <?php echo e(date('h:i A', strtotime($log->closed_at))); ?>

                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span style="color:var(--ink-4);">—</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <span class="cl-pump">
                                <i class="bi bi-fuel-pump" style="font-size:0.65rem;"></i>
                                <?php echo e($log->pump_name); ?>

                            </span>
                        </td>

                        <td>
                            <span class="cl-fuel">
                                <i class="bi bi-droplet-fill" style="font-size:0.65rem;"></i>
                                <?php echo e($log->fuel_name); ?>

                            </span>
                        </td>

                        <td class="text-end">
                            <span class="cl-mono-bold"><?php echo e(number_format($log->reading, 3)); ?></span>
                            <span class="cl-mono" style="color:var(--ink-3);margin-left:2px;">L</span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4">
                            <div class="cl-empty">
                                <i class="bi bi-speedometer2 cl-empty-icon"></i>
                                <h5>No totalizer records found</h5>
                                <p>Try adjusting your filters or close a shift to generate a log</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($logs->hasPages()): ?>
        <div class="cl-pagination">
            <span class="cl-page-info">
                Page <?php echo e($logs->currentPage()); ?> of <?php echo e($logs->lastPage()); ?>

                &nbsp;&middot;&nbsp;
                <?php echo e(number_format($logs->total())); ?> records
            </span>
            <?php echo e($logs->appends(request()->query())->links('pagination::bootstrap-4')); ?>

        </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\john mark bolanon\laravel\Seal-gasolineStation\resources\views/totalizer/index.blade.php ENDPATH**/ ?>