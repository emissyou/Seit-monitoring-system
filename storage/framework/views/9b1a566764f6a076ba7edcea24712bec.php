

<?php $__env->startSection('title', 'Credit Logs'); ?>

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
}

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

.cl-badge-paid    { background: var(--green-bg);  color: #007a5a; border: 1px solid var(--green-border); }
.cl-badge-partial { background: var(--amber-bg);  color: #92400e; border: 1px solid var(--amber-border); }
.cl-badge-unpaid  { background: var(--red-bg);    color: var(--red); border: 1px solid var(--red-border); }

/* ─── Mono values ────────────────────────────────────────── */
.cl-mono      { font-family: var(--font-mono); font-size: 0.82rem; font-weight: 500; }
.cl-mono-bold { font-family: var(--font-mono); font-size: 0.85rem; font-weight: 600; color: var(--ink-1); }

/* ─── Balance Bar ────────────────────────────────────────── */
.cl-balance-wrap { min-width: 105px; }

.cl-balance-bar {
    height: 4px;
    background: var(--border);
    border-radius: 999px;
    margin-top: 5px;
    overflow: hidden;
}

.cl-balance-fill {
    height: 100%;
    border-radius: 999px;
    transition: width 0.5s cubic-bezier(0.4,0,0.2,1);
}

/* ─── Action Dropdown ────────────────────────────────────── */
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

.cl-action-btn:hover { background: var(--ink-1); border-color: var(--ink-1); color: #fff; }

.dropdown-menu {
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12);
    font-size: 0.83rem;
    padding: 0.35rem;
    min-width: 160px;
    font-family: var(--font-body);
}

.dropdown-item {
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-weight: 500;
    font-family: var(--font-body);
    color: var(--ink-2);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: background 0.15s;
}

.dropdown-item:hover { background: var(--surface-2); color: var(--ink-1); }
.dropdown-item.cl-archive { color: #92400e; }
.dropdown-item.cl-archive:hover { background: var(--amber-bg); }

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

.cl-detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 0.75rem;
    margin-bottom: 1.75rem;
}

.cl-detail-item {
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 0.85rem 1rem;
}

.cl-detail-item-label {
    font-size: 0.62rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--ink-3);
    margin-bottom: 0.3rem;
    font-family: var(--font-body);
}

.cl-detail-item-value { font-size: 1rem; font-weight: 700; color: var(--ink-1); font-family: var(--font-mono); }

.cl-payment-section-title {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--ink-3);
    margin-bottom: 0.75rem;
    font-family: var(--font-body);
}

.cl-payment-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    background: var(--surface-2);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-sm);
    margin-bottom: 0.5rem;
    font-size: 0.83rem;
    transition: border-color 0.15s;
    font-family: var(--font-body);
}

.cl-payment-row:hover { border-color: var(--green-border); }
.cl-payment-amount { font-family: var(--font-mono); font-weight: 700; color: var(--green); font-size: 0.88rem; }

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
        <div class="cl-eyebrow">Finance &bull; Credit</div>
        <h1 class="cl-page-title">Credit Logs</h1>
        <p class="cl-page-sub">Track and manage customer credit transactions</p>
    </div>
    <a href="<?php echo e(route('credits.export', request()->query())); ?>" class="btn-export">
        <i class="bi bi-download"></i> Export CSV
    </a>
</div>


<?php
    $allCredits     = \App\Models\CreditView::when(request('customer_id'), fn($q) => $q->where('CustomerID', request('customer_id')))->get();
    $totalAmount    = $allCredits->sum('total_amount');
    $totalPaid      = $allCredits->sum('amount_paid');
    $totalRemaining = $allCredits->sum('remaining_balance');
    $countUnpaid    = $allCredits->where('status', 'unpaid')->count();
?>

<div class="cl-summary-grid">

    <div class="cl-stat">
        <div class="cl-stat-glow" style="background:#e8304a;"></div>
        <div class="cl-stat-icon" style="background:var(--red-bg);color:var(--red);">
            <i class="bi bi-credit-card-2-front-fill"></i>
        </div>
        <div class="cl-stat-label">Total Credit Issued</div>
        <div class="cl-stat-value">&#8369;<?php echo e(number_format($totalAmount, 2)); ?></div>
        <div class="cl-stat-sub"><?php echo e($allCredits->count()); ?> transaction<?php echo e($allCredits->count() !== 1 ? 's' : ''); ?></div>
    </div>

    <div class="cl-stat">
        <div class="cl-stat-glow" style="background:#00b37e;"></div>
        <div class="cl-stat-icon" style="background:var(--green-bg);color:var(--green);">
            <i class="bi bi-cash-stack"></i>
        </div>
        <div class="cl-stat-label">Total Collected</div>
        <div class="cl-stat-value">&#8369;<?php echo e(number_format($totalPaid, 2)); ?></div>
        <div class="cl-stat-sub"><?php echo e($allCredits->where('status','paid')->count()); ?> fully paid</div>
    </div>

    <div class="cl-stat">
        <div class="cl-stat-glow" style="background:#f59e0b;"></div>
        <div class="cl-stat-icon" style="background:var(--amber-bg);color:var(--amber);">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="cl-stat-label">Outstanding Balance</div>
        <div class="cl-stat-value">&#8369;<?php echo e(number_format($totalRemaining, 2)); ?></div>
        <div class="cl-stat-sub"><?php echo e($allCredits->where('status','partial')->count()); ?> partial</div>
    </div>

    <div class="cl-stat">
        <div class="cl-stat-glow" style="background:#3b6ef6;"></div>
        <div class="cl-stat-icon" style="background:var(--blue-bg);color:var(--blue);">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="cl-stat-label">Unpaid Credits</div>
        <div class="cl-stat-value"><?php echo e($countUnpaid); ?></div>
        <div class="cl-stat-sub">no payment received</div>
    </div>

</div>


<div class="cl-filter-bar">
    <form method="GET" action="<?php echo e(route('credits.index')); ?>" class="row g-3 align-items-end">

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
            <label class="cl-filter-label">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="unpaid"  <?php echo e(request('status') === 'unpaid'  ? 'selected' : ''); ?>>Unpaid</option>
                <option value="partial" <?php echo e(request('status') === 'partial' ? 'selected' : ''); ?>>Partial</option>
                <option value="paid"    <?php echo e(request('status') === 'paid'    ? 'selected' : ''); ?>>Paid</option>
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

        <div class="col-md-3 d-flex gap-2 align-items-end">
            <button type="submit" class="btn-apply flex-grow-1">
                <i class="bi bi-funnel-fill"></i> Apply Filter
            </button>
            <a href="<?php echo e(route('credits.index')); ?>" class="btn-reset" title="Clear filters">
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
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Fuel</th>
                    <th class="text-end">Qty (L)</th>
                    <th class="text-end">Price / L</th>
                    <th class="text-end">Discount</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Paid</th>
                    <th class="text-end">Remaining</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $credits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $credit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $pct = $credit->total_amount > 0
                            ? min(100, ($credit->amount_paid / $credit->total_amount) * 100)
                            : 0;
                        $initials = strtoupper(
                            collect(explode(' ', $credit->customer_name))
                                ->map(fn($w) => substr($w, 0, 1))
                                ->take(2)
                                ->implode('')
                        );
                        $fillColor = $pct >= 100 ? '#00b37e' : ($pct > 0 ? '#f59e0b' : '#e8304a');
                    ?>
                    <tr>
                        <td>
                            <span class="cl-mono" style="color:var(--ink-2);">
                                <?php echo e($credit->credit_date?->format('M d, Y')); ?>

                            </span>
                        </td>

                        <td>
                            <div class="cl-customer">
                                <div class="cl-avatar"><?php echo e($initials); ?></div>
                                <span class="cl-customer-name"><?php echo e($credit->customer_name); ?></span>
                            </div>
                        </td>

                        <td>
                            <span class="cl-fuel">
                                <i class="bi bi-droplet-fill" style="font-size:0.65rem;"></i>
                                <?php echo e($credit->fuel_name ?? '—'); ?>

                            </span>
                        </td>

                        <td class="text-end cl-mono-bold">
                            <?php echo e(number_format($credit->Quantity, 3)); ?>

                        </td>

                        <td class="text-end cl-mono" style="color:var(--ink-2);">
                            &#8369;<?php echo e(number_format($credit->price_per_liter, 2)); ?>

                        </td>

                        <td class="text-end cl-mono" style="color:var(--red);">
                            <?php if($credit->discount_amount > 0): ?>
                                &minus;&#8369;<?php echo e(number_format($credit->discount_amount, 2)); ?>

                            <?php else: ?>
                                <span style="color:var(--ink-4);">&mdash;</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-end cl-mono-bold">
                            &#8369;<?php echo e(number_format($credit->total_amount, 2)); ?>

                        </td>

                        <td class="text-end cl-mono" style="color:var(--green);font-weight:700;">
                            &#8369;<?php echo e(number_format($credit->amount_paid, 2)); ?>

                        </td>

                        <td class="text-end">
                            <div class="cl-balance-wrap">
                                <span class="cl-mono" style="font-weight:700;color:<?php echo e($credit->remaining_balance > 0 ? 'var(--red)' : 'var(--ink-4)'); ?>;">
                                    &#8369;<?php echo e(number_format($credit->remaining_balance, 2)); ?>

                                </span>
                                <div class="cl-balance-bar">
                                    <div class="cl-balance-fill" style="width:<?php echo e($pct); ?>%;background:<?php echo e($fillColor); ?>;"></div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="cl-badge cl-badge-<?php echo e($credit->status ?? 'unpaid'); ?>">
                                <?php echo e(ucfirst($credit->status ?? 'unpaid')); ?>

                            </span>
                        </td>

                        <td class="text-center">
                            <div class="dropdown">
                                <button class="cl-action-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#"
                                           onclick="viewCreditDetail(<?php echo e($credit->CreditID); ?>)">
                                            <i class="bi bi-eye"></i> View Detail
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item cl-archive" href="#"
                                           onclick="archiveCredit(<?php echo e($credit->CreditID); ?>)">
                                            <i class="bi bi-archive"></i> Archive
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="11">
                            <div class="cl-empty">
                                <i class="bi bi-credit-card cl-empty-icon"></i>
                                <h5>No credit records found</h5>
                                <p>Try adjusting your filters</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($credits->hasPages()): ?>
        <div class="cl-pagination">
            <?php echo e($credits->appends(request()->query())->links()); ?>

        </div>
    <?php endif; ?>
</div>



<div class="modal fade" id="creditDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="cl-modal-header">
                <div class="cl-modal-eyebrow">Credit Record</div>
                <div class="cl-modal-title" id="modalCustomerName">&mdash;</div>
                <button type="button" class="cl-modal-close" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="cl-detail-grid">
                    <div class="cl-detail-item">
                        <div class="cl-detail-item-label">Date</div>
                        <div class="cl-detail-item-value" id="dDate">&mdash;</div>
                    </div>
                    <div class="cl-detail-item">
                        <div class="cl-detail-item-label">Fuel Type</div>
                        <div class="cl-detail-item-value" id="dFuel">&mdash;</div>
                    </div>
                    <div class="cl-detail-item">
                        <div class="cl-detail-item-label">Quantity</div>
                        <div class="cl-detail-item-value" id="dQty">&mdash;</div>
                    </div>
                    <div class="cl-detail-item">
                        <div class="cl-detail-item-label">Price / Liter</div>
                        <div class="cl-detail-item-value" id="dPrice">&mdash;</div>
                    </div>
                    <div class="cl-detail-item">
                        <div class="cl-detail-item-label">Discount</div>
                        <div class="cl-detail-item-value" id="dDiscount" style="color:var(--red);">&mdash;</div>
                    </div>
                    <div class="cl-detail-item">
                        <div class="cl-detail-item-label">Total Amount</div>
                        <div class="cl-detail-item-value" id="dTotal">&mdash;</div>
                    </div>
                    <div class="cl-detail-item">
                        <div class="cl-detail-item-label">Amount Paid</div>
                        <div class="cl-detail-item-value" id="dPaid" style="color:var(--green);">&mdash;</div>
                    </div>
                    <div class="cl-detail-item">
                        <div class="cl-detail-item-label">Remaining Balance</div>
                        <div class="cl-detail-item-value" id="dRemaining" style="color:var(--red);">&mdash;</div>
                    </div>
                </div>

                <div class="cl-payment-section-title">Payment History</div>
                <div id="dPayments"></div>
            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
<script>
function viewCreditDetail(id) {
    fetch(`/credits/${id}/detail`)
        .then(r => r.json())
        .then(d => {
            const fmt = v => '\u20B1' + parseFloat(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 });

            document.getElementById('modalCustomerName').textContent = d.customer_name || '\u2014';
            document.getElementById('dDate').textContent             = d.date          || '\u2014';
            document.getElementById('dFuel').textContent             = d.fuel_type     || '\u2014';
            document.getElementById('dQty').textContent              = parseFloat(d.liters || 0).toFixed(3) + ' L';
            document.getElementById('dPrice').textContent            = fmt(d.price);
            document.getElementById('dDiscount').textContent         = d.discount > 0 ? '\u2212' + fmt(d.discount) : '\u2014';
            document.getElementById('dTotal').textContent            = fmt(d.amount);
            document.getElementById('dPaid').textContent             = fmt(d.amount_paid);
            document.getElementById('dRemaining').textContent        = fmt(d.remaining_balance);

            const box = document.getElementById('dPayments');
            if (d.payments && d.payments.length > 0) {
                box.innerHTML = d.payments.map(p => `
                    <div class="cl-payment-row">
                        <span style="color:var(--ink-2);font-weight:500;">
                            ${p.payment_date}${p.note ? ' &middot; <em style="color:var(--ink-3);">' + p.note + '</em>' : ''}
                        </span>
                        <span class="cl-payment-amount">${fmt(p.amount_paid)}</span>
                    </div>
                `).join('');
            } else {
                box.innerHTML = `
                    <div style="text-align:center;padding:2.5rem 1rem;color:var(--ink-4);font-family:var(--font-body);">
                        <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:0.5rem;opacity:0.3;"></i>
                        No payments recorded yet.
                    </div>`;
            }

            new bootstrap.Modal(document.getElementById('creditDetailModal')).show();
        })
        .catch(() => alert('Failed to load credit details.'));
}

function archiveCredit(id) {
    if (confirm('Are you sure you want to archive this credit record?')) {
        fetch(`/credits/${id}/archive`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        }).then(r => r.ok ? location.reload() : alert('Failed to archive record.'));
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\john mark bolanon\laravel\Seal-gasolineStation\resources\views/credits/index.blade.php ENDPATH**/ ?>