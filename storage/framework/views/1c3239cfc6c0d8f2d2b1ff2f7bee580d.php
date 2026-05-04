

<?php $__env->startSection('title', 'Discount Management'); ?>

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

    --purple:       #7c3aed;
    --purple-bg:    #f5f3ff;
    --purple-border:#ddd6fe;

    --radius:    14px;
    --radius-sm: 8px;

    --shadow-card:  0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    --shadow-hover: 0 8px 28px rgba(0,0,0,0.10);
    --shadow-modal: 0 32px 80px rgba(0,0,0,0.18);

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

.btn-new {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.3rem;
    font-size: 0.8rem;
    font-weight: 600;
    font-family: var(--font-body);
    background: var(--red);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: background 0.2s, transform 0.15s;
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(232,48,74,0.28);
    text-decoration: none;
}
.btn-new:hover { background: #c8243d; color: #fff; transform: translateY(-1px); }

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

/* ─── Customer Chip ──────────────────────────────────────── */
.cl-customer { display: flex; align-items: center; gap: 0.65rem; }

.cl-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e8304a, #ff7043);
    color: #fff;
    font-size: 0.7rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 6px rgba(232,48,74,0.35);
}

.cl-customer-name { font-weight: 600; color: var(--ink-1); font-size: 0.85rem; }

/* ─── Type Badge ─────────────────────────────────────────── */
.cl-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.28rem 0.72rem;
    border-radius: 6px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.3px;
    white-space: nowrap;
    font-family: var(--font-body);
}
.cl-type-per-liter    { background: var(--purple-bg); color: var(--purple); border: 1px solid var(--purple-border); }
.cl-type-fixed-amount { background: var(--blue-bg);   color: var(--blue);   border: 1px solid var(--blue-border); }
.cl-type-percentage   { background: var(--amber-bg);  color: var(--amber);  border: 1px solid var(--amber-border); }

/* ─── Discount Value Badge ───────────────────────────────── */
.cl-discount-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.28rem 0.72rem;
    background: var(--red-bg);
    border: 1px solid var(--red-border);
    border-radius: 6px;
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--red);
    letter-spacing: 0.3px;
    white-space: nowrap;
    font-family: var(--font-mono);
}
.cl-discount-badge i { font-size: 0.62rem; opacity: 0.85; }

/* ─── Status Badge ───────────────────────────────────────── */
.cl-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.3rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    white-space: nowrap;
    font-family: var(--font-body);
}
.cl-badge::before {
    content: '';
    width: 6px; height: 6px;
    border-radius: 50%;
    background: currentColor;
    flex-shrink: 0;
}
.cl-badge-active   { background: var(--green-bg); color: #007a5a; border: 1px solid var(--green-border); }
.cl-badge-inactive { background: var(--amber-bg); color: #92400e; border: 1px solid var(--amber-border); }
.cl-badge-expired  { background: var(--surface-2); color: var(--ink-3); border: 1px solid var(--border); }

/* ─── Validity Bar ───────────────────────────────────────── */
.cl-validity-wrap { min-width: 130px; }

.cl-validity-dates {
    font-size: 0.72rem;
    color: var(--ink-3);
    margin-bottom: 5px;
    white-space: nowrap;
    font-family: var(--font-body);
}
.cl-validity-dates b { color: var(--ink-2); font-weight: 600; }
.cl-validity-dates .arr { color: var(--ink-4); margin: 0 3px; }

.cl-validity-bar {
    height: 4px;
    background: var(--border);
    border-radius: 999px;
    overflow: hidden;
}
.cl-validity-fill {
    height: 100%;
    border-radius: 999px;
    transition: width 0.5s cubic-bezier(0.4,0,0.2,1);
}

/* ─── Description Cell ───────────────────────────────────── */
.cl-desc {
    max-width: 160px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 0.78rem;
    color: var(--ink-3);
    display: block;
}

/* ─── Action Button ──────────────────────────────────────── */
.cl-action-btn {
    width: 32px; height: 32px;
    border-radius: var(--radius-sm);
    background: var(--surface-2);
    border: 1.5px solid var(--border);
    color: var(--ink-3);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.2s;
}
.cl-action-btn:hover { background: var(--red-bg); border-color: var(--red-border); color: var(--red); }

/* ─── Empty State ────────────────────────────────────────── */
.cl-empty { padding: 4.5rem 1rem; text-align: center; color: var(--ink-4); }
.cl-empty-icon { font-size: 3rem; opacity: 0.2; margin-bottom: 1rem; display: block; }
.cl-empty h5 { font-size: 1rem; font-weight: 700; color: var(--ink-3); margin-bottom: 0.4rem; font-family: var(--font-body); }
.cl-empty p  { font-size: 0.83rem; margin: 0; }

/* ─── Pagination ─────────────────────────────────────────── */
.cl-pagination { padding: 1rem 1.5rem; border-top: 1px solid var(--border-soft); background: var(--surface-2); }

/* ─── Modal ──────────────────────────────────────────────── */
.modal-content {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-modal);
    font-family: var(--font-body);
}

.cl-modal-header {
    padding: 1.75rem 2rem 1.5rem;
    background: var(--ink-1);
    color: #fff;
    position: relative;
}
.cl-modal-header::after {
    content: '';
    position: absolute;
    bottom: 0; left: 2rem;
    width: 40px; height: 3px;
    background: var(--red);
    border-radius: 3px 3px 0 0;
}

.cl-modal-eyebrow {
    font-size: 0.62rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    opacity: 0.45;
    margin-bottom: 0.25rem;
    font-family: var(--font-body);
}
.cl-modal-title { font-size: 1.3rem; font-weight: 800; margin: 0; letter-spacing: -0.3px; font-family: var(--font-body); }

.cl-modal-close {
    background: rgba(255,255,255,0.12);
    border-radius: 8px;
    width: 34px; height: 34px;
    display: flex; align-items: center; justify-content: center;
    border: none; cursor: pointer;
    color: #fff; font-size: 1rem;
    transition: background 0.2s;
    position: absolute;
    top: 1.5rem; right: 1.5rem;
}
.cl-modal-close:hover { background: rgba(255,255,255,0.22); }

.modal-body { padding: 1.75rem 2rem; background: #fff; }

.modal-footer {
    padding: 1rem 2rem 1.5rem;
    background: #fff;
    border-top: 1px solid var(--border-soft);
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
}

.cl-modal-body .form-label {
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--ink-3);
    margin-bottom: 0.35rem;
    display: block;
    font-family: var(--font-body);
}
.cl-modal-body .form-select,
.cl-modal-body .form-control,
.cl-modal-body textarea {
    font-family: var(--font-body);
    font-size: 0.85rem;
    font-weight: 500;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    background: var(--surface-2);
    color: var(--ink-1);
    padding: 0.5rem 0.85rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.cl-modal-body .form-select:focus,
.cl-modal-body .form-control:focus,
.cl-modal-body textarea:focus {
    outline: none;
    border-color: var(--red);
    box-shadow: 0 0 0 3px rgba(232,48,74,0.1);
    background: var(--surface);
}

.btn-modal-cancel {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.55rem 1.2rem;
    font-size: 0.8rem; font-weight: 600; font-family: var(--font-body);
    background: var(--surface-2);
    color: var(--ink-2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: all 0.2s;
}
.btn-modal-cancel:hover { background: var(--border); }

.btn-modal-submit {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.55rem 1.4rem;
    font-size: 0.8rem; font-weight: 600; font-family: var(--font-body);
    background: var(--red);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: background 0.2s, transform 0.15s;
    box-shadow: 0 2px 8px rgba(232,48,74,0.28);
}
.btn-modal-submit:hover { background: #c8243d; transform: translateY(-1px); }

/* ─── Animations ─────────────────────────────────────────── */
@keyframes cl-fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}
.cl-page-header               { animation: cl-fadeUp 0.35s ease both; }
.cl-stat                      { animation: cl-fadeUp 0.4s ease both; }
.cl-stat:nth-child(1)         { animation-delay: 0.05s; }
.cl-stat:nth-child(2)         { animation-delay: 0.10s; }
.cl-stat:nth-child(3)         { animation-delay: 0.15s; }
.cl-stat:nth-child(4)         { animation-delay: 0.20s; }
.cl-filter-bar                { animation: cl-fadeUp 0.4s 0.22s ease both; }
.cl-table-card                { animation: cl-fadeUp 0.4s 0.28s ease both; }

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
        <div class="cl-eyebrow">Finance &bull; Discounts</div>
        <h1 class="cl-page-title">Discount Management</h1>
        <p class="cl-page-sub">Create and manage customer discounts with time-bound validity</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('discounts.export', request()->query())); ?>" class="btn-export">
            <i class="bi bi-download"></i> Export CSV
        </a>
        <button class="btn-new" data-bs-toggle="modal" data-bs-target="#addDiscountModal">
            <i class="bi bi-plus-lg"></i> New Discount
        </button>
    </div>
</div>


<?php
    $allDiscounts  = \App\Models\DiscountView::all();
    $activeCount   = $allDiscounts->where('is_active', true)
                        ->filter(fn($d) => !($d->end_date && $d->end_date->lt(now())))->count();
    $inactiveCount = $allDiscounts->where('is_active', false)->count();
    $expiredCount  = $allDiscounts->filter(fn($d) => $d->end_date && $d->end_date->lt(now()))->count();
    $perLiterCount = $allDiscounts->where('discount_type', 'per_liter')->count();
?>

<div class="cl-summary-grid">

    <div class="cl-stat">
        <div class="cl-stat-glow" style="background:#00b37e;"></div>
        <div class="cl-stat-icon" style="background:var(--green-bg);color:var(--green);">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="cl-stat-label">Active Discounts</div>
        <div class="cl-stat-value"><?php echo e($activeCount); ?></div>
        <div class="cl-stat-sub">currently valid</div>
    </div>

    <div class="cl-stat">
        <div class="cl-stat-glow" style="background:#f59e0b;"></div>
        <div class="cl-stat-icon" style="background:var(--amber-bg);color:var(--amber);">
            <i class="bi bi-pause-circle-fill"></i>
        </div>
        <div class="cl-stat-label">Inactive</div>
        <div class="cl-stat-value"><?php echo e($inactiveCount); ?></div>
        <div class="cl-stat-sub">not yet applied</div>
    </div>

    <div class="cl-stat">
        <div class="cl-stat-glow" style="background:#e8304a;"></div>
        <div class="cl-stat-icon" style="background:var(--red-bg);color:var(--red);">
            <i class="bi bi-clock-history"></i>
        </div>
        <div class="cl-stat-label">Expired</div>
        <div class="cl-stat-value"><?php echo e($expiredCount); ?></div>
        <div class="cl-stat-sub">past end date</div>
    </div>

    <div class="cl-stat">
        <div class="cl-stat-glow" style="background:#7c3aed;"></div>
        <div class="cl-stat-icon" style="background:var(--purple-bg);color:var(--purple);">
            <i class="bi bi-tags-fill"></i>
        </div>
        <div class="cl-stat-label">Per-Liter Type</div>
        <div class="cl-stat-value"><?php echo e($perLiterCount); ?></div>
        <div class="cl-stat-sub"><?php echo e($allDiscounts->count()); ?> total discounts</div>
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
    <form method="GET" action="<?php echo e(route('discounts.index')); ?>" class="row g-3 align-items-end">

        <div class="col-md-3">
            <label class="cl-filter-label">Customer</label>
            <select name="customer_id" class="form-select form-select-sm">
                <option value="">All Customers</option>
                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($customer->CustomerID); ?>"
                        <?php echo e(request('customer_id') == $customer->CustomerID ? 'selected' : ''); ?>>
                        <?php echo e($customer->First_name); ?> <?php echo e($customer->Last_name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="col-md-2">
            <label class="cl-filter-label">Type</label>
            <select name="discount_type" class="form-select form-select-sm">
                <option value="">All Types</option>
                <option value="per_liter"    <?php echo e(request('discount_type') === 'per_liter'    ? 'selected' : ''); ?>>Per Liter</option>
                <option value="fixed_amount" <?php echo e(request('discount_type') === 'fixed_amount' ? 'selected' : ''); ?>>Fixed Amount</option>
                <option value="percentage"   <?php echo e(request('discount_type') === 'percentage'   ? 'selected' : ''); ?>>Percentage</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="cl-filter-label">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="active"   <?php echo e(request('status') === 'active'   ? 'selected' : ''); ?>>Active</option>
                <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                <option value="expired"  <?php echo e(request('status') === 'expired'  ? 'selected' : ''); ?>>Expired</option>
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

        <div class="col-md-1 d-flex gap-2 align-items-end">
            <button type="submit" class="btn-apply flex-grow-1">
                <i class="bi bi-funnel-fill"></i>
            </button>
            <a href="<?php echo e(route('discounts.index')); ?>" class="btn-reset" title="Clear filters">
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
                    <th>Customer</th>
                    <th>Type</th>
                    <th class="text-end">Value</th>
                    <th>Validity</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $discounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $discount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $initials = strtoupper(
                            collect(explode(' ', $discount->customer_name))
                                ->map(fn($w) => substr($w, 0, 1))
                                ->take(2)
                                ->implode('')
                        );

                        $typeClass = match($discount->discount_type) {
                            'per_liter'    => 'cl-type-per-liter',
                            'fixed_amount' => 'cl-type-fixed-amount',
                            'percentage'   => 'cl-type-percentage',
                            default        => ''
                        };
                        $typeLabel = match($discount->discount_type) {
                            'per_liter'    => 'Per Liter',
                            'fixed_amount' => 'Fixed Amount',
                            'percentage'   => 'Percentage',
                            default        => ucfirst(str_replace('_', ' ', $discount->discount_type))
                        };

                        $isExpired = $discount->end_date && $discount->end_date->lt(now());
                        $totalDays = ($discount->start_date && $discount->end_date)
                            ? max(1, $discount->start_date->diffInDays($discount->end_date)) : 1;
                        $elapsed   = $discount->start_date
                            ? min($totalDays, max(0, $discount->start_date->diffInDays(now()))) : 0;
                        $pct       = round(($elapsed / $totalDays) * 100);
                        $barColor  = $isExpired ? '#e8304a' : ($pct > 75 ? '#f59e0b' : '#00b37e');

                        $statusClass = $isExpired ? 'cl-badge-expired'
                            : ($discount->is_active ? 'cl-badge-active' : 'cl-badge-inactive');
                        $statusLabel = $isExpired ? 'Expired'
                            : ($discount->is_active ? 'Active' : 'Inactive');
                    ?>
                    <tr>
                        <td>
                            <div class="cl-customer">
                                <div class="cl-avatar"><?php echo e($initials); ?></div>
                                <div>
                                    <div class="cl-customer-name"><?php echo e($discount->customer_name); ?></div>
                                    <div style="font-size:0.72rem;color:var(--ink-3);">#D-<?php echo e(str_pad($discount->DiscountID, 4, '0', STR_PAD_LEFT)); ?></div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="cl-type-badge <?php echo e($typeClass); ?>"><?php echo e($typeLabel); ?></span>
                        </td>

                        <td class="text-end">
                            <span class="cl-discount-badge">
                                <i class="bi bi-tag-fill"></i>
                                <?php if($discount->discount_type === 'percentage'): ?>
                                    <?php echo e(number_format($discount->discount_value, 2)); ?>%
                                <?php else: ?>
                                    &#8369;<?php echo e(number_format($discount->discount_value, 2)); ?>

                                <?php endif; ?>
                            </span>
                        </td>

                        <td>
                            <div class="cl-validity-wrap">
                                <div class="cl-validity-dates">
                                    <b><?php echo e($discount->start_date?->format('M d') ?? '—'); ?></b>
                                    <span class="arr">→</span>
                                    <b><?php echo e($discount->end_date?->format('M d, Y') ?? '—'); ?></b>
                                </div>
                                <div class="cl-validity-bar">
                                    <div class="cl-validity-fill" style="width:<?php echo e($pct); ?>%;background:<?php echo e($barColor); ?>;"></div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="cl-desc" title="<?php echo e($discount->description); ?>">
                                <?php echo e($discount->description ?? '—'); ?>

                            </span>
                        </td>

                        <td>
                            <span class="cl-badge <?php echo e($statusClass); ?>"><?php echo e($statusLabel); ?></span>
                        </td>

                        <td class="text-center">
                            <button class="cl-action-btn"
                                    onclick="archiveDiscount(<?php echo e($discount->DiscountID); ?>)"
                                    title="Archive">
                                <i class="bi bi-archive"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7">
                            <div class="cl-empty">
                                <i class="bi bi-tag cl-empty-icon"></i>
                                <h5>No discounts found</h5>
                                <p>Try adjusting your filters or create a new discount</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($discounts->hasPages()): ?>
        <div class="cl-pagination">
            <?php echo e($discounts->appends(request()->query())->links('pagination::bootstrap-4')); ?>

        </div>
    <?php endif; ?>
</div>



<div class="modal fade" id="addDiscountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="cl-modal-header">
                <div class="cl-modal-eyebrow">Finance &bull; Discounts</div>
                <div class="cl-modal-title">Create New Discount</div>
                <button type="button" class="cl-modal-close" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body cl-modal-body">
                <form id="discountForm" method="POST" action="<?php echo e(route('discounts.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer <span style="color:var(--red)">*</span></label>
                            <select name="customer_id" class="form-select" required>
                                <option value="">Select customer…</option>
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer->CustomerID); ?>">
                                        <?php echo e($customer->First_name); ?> <?php echo e($customer->Last_name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Discount Type <span style="color:var(--red)">*</span></label>
                            <select name="discount_type" class="form-select" required>
                                <option value="per_liter">Per Liter</option>
                                <option value="fixed_amount">Fixed Amount</option>
                                <option value="percentage">Percentage (%)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Value <span style="color:var(--red)">*</span></label>
                            <input type="number" step="0.01" min="0" name="discount_value"
                                   class="form-control" placeholder="e.g. 2.50" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Start Date <span style="color:var(--red)">*</span></label>
                            <input type="date" name="start_date" class="form-control"
                                   value="<?php echo e(date('Y-m-d')); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">End Date <span style="color:var(--red)">*</span></label>
                            <input type="date" name="end_date" class="form-control"
                                   value="<?php echo e(date('Y-m-d', strtotime('+30 days'))); ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                Description
                                <span style="color:var(--ink-4);text-transform:none;letter-spacing:0;font-size:0.7rem;font-weight:400;">— Optional</span>
                            </label>
                            <textarea name="description" class="form-control" rows="3"
                                      placeholder="Promotional discount for loyal customers…"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="button" class="btn-modal-submit"
                        onclick="document.getElementById('discountForm').submit()">
                    <i class="bi bi-plus-lg"></i> Create Discount
                </button>
            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
<script>
function archiveDiscount(id) {
    if (confirm('Archive this discount? It will no longer appear in active filters.')) {
        fetch(`/discounts/${id}/archive`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(r => {
            if (r.ok) location.reload();
            else alert('Failed to archive discount. Please try again.');
        })
        .catch(() => alert('Network error. Please try again.'));
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\john mark bolanon\laravel\Seal-gasolineStation\resources\views/discounts/index.blade.php ENDPATH**/ ?>