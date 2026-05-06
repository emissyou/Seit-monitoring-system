
<?php $__env->startSection('title', 'Customers'); ?>
<?php $__env->startSection('content'); ?>

<style>
/* ── Credit-Logs theme variables ── */
:root {
    --cl-red:       #e8192c;
    --cl-red-dark:  #c0141f;
    --cl-red-light: #fff0f1;
    --cl-border:    #e9ecef;
    --cl-surface:   #ffffff;
    --cl-bg:        #f8f9fa;
    --cl-text:      #111827;
    --cl-muted:     #6b7280;
    --cl-green:     #10b981;
    --cl-amber:     #f59e0b;
}

/* ── Page header ── */
.cl-page-header {
    border-left: 4px solid var(--cl-red);
    padding-left: 1rem;
}
.cl-page-header .cl-breadcrumb {
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--cl-muted);
    margin-bottom: 0.2rem;
}
.cl-page-header h1 {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--cl-text);
    margin: 0;
    line-height: 1.2;
}
.cl-page-header p {
    color: var(--cl-muted);
    font-size: 0.85rem;
    margin: 0;
}

/* ── Buttons ── */
.btn-cl-primary {
    background: var(--cl-red);
    border: none;
    color: #fff;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 0.5rem 1.1rem;
    border-radius: 8px;
    transition: background 0.15s;
}
.btn-cl-primary:hover { background: var(--cl-red-dark); color: #fff; }
.btn-cl-outline {
    background: #fff;
    border: 1.5px solid var(--cl-border);
    color: var(--cl-text);
    font-weight: 500;
    font-size: 0.85rem;
    padding: 0.5rem 1.1rem;
    border-radius: 8px;
    transition: border-color 0.15s, background 0.15s;
}
.btn-cl-outline:hover { border-color: #adb5bd; background: var(--cl-bg); }

/* ── Cards ── */
.cl-card {
    background: var(--cl-surface);
    border: 1px solid var(--cl-border);
    border-radius: 14px;
    padding: 1.5rem;
}

/* ── Filter bar ── */
.cl-search-group .input-group-text {
    background: #fff;
    border-right: none;
    border-color: var(--cl-border);
    color: var(--cl-muted);
}
.cl-search-group .form-control {
    border-left: none;
    border-color: var(--cl-border);
    font-size: 0.875rem;
}
.cl-search-group .form-control:focus { box-shadow: none; border-color: #adb5bd; }
.cl-filter-pill {
    font-size: 0.78rem;
    font-weight: 600;
    padding: 0.35rem 0.9rem;
    border-radius: 999px;
    border: 1.5px solid var(--cl-border);
    color: var(--cl-muted);
    background: #fff;
    text-decoration: none;
    transition: all 0.15s;
}
.cl-filter-pill:hover { border-color: #adb5bd; color: var(--cl-text); }
.cl-filter-pill.active-all { background: var(--cl-text); border-color: var(--cl-text); color: #fff; }
.cl-filter-pill.active-danger { background: var(--cl-red); border-color: var(--cl-red); color: #fff; }
.cl-filter-pill.active-success { background: var(--cl-green); border-color: var(--cl-green); color: #fff; }

/* ── Table ── */
.cl-table { width: 100%; border-collapse: separate; border-spacing: 0; }
.cl-table thead th {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--cl-muted);
    padding: 0.65rem 1rem;
    border-bottom: 1px solid var(--cl-border);
    background: var(--cl-bg);
    white-space: nowrap;
}
.cl-table tbody td {
    padding: 0.85rem 1rem;
    border-bottom: 1px solid #f3f4f6;
    font-size: 0.875rem;
    color: var(--cl-text);
    vertical-align: middle;
}
.cl-table tbody tr:last-child td { border-bottom: none; }
.cl-table tbody tr:hover td { background: #fafafa; }

/* ── Avatar ── */
.cl-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: var(--cl-red);
    color: #fff;
    font-size: 0.75rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    text-transform: uppercase;
}

/* ── Badges ── */
.cl-badge {
    display: inline-flex; align-items: center; gap: 0.3rem;
    font-size: 0.72rem; font-weight: 600;
    padding: 0.25rem 0.65rem;
    border-radius: 999px;
}
.cl-badge-success { background: #d1fae5; color: #065f46; }
.cl-badge-secondary { background: #f3f4f6; color: #374151; }
.cl-badge-danger { background: #fee2e2; color: #991b1b; }

/* ── Three-dot action button ── */
.cl-action-btn {
    width: 30px; height: 30px;
    border-radius: 8px;
    border: 1.5px solid var(--cl-border);
    background: #fff;
    color: var(--cl-muted);
    display: inline-flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.15s;
}
.cl-action-btn:hover { border-color: #adb5bd; color: var(--cl-text); background: var(--cl-bg); }

/* ── Pagination ── */
.cl-page-btn {
    min-width: 32px; height: 32px;
    border-radius: 8px;
    border: 1.5px solid var(--cl-border);
    background: #fff;
    color: var(--cl-text);
    font-size: 0.82rem; font-weight: 500;
    display: inline-flex; align-items: center; justify-content: center;
    padding: 0 0.5rem;
    text-decoration: none; transition: all 0.15s;
}
.cl-page-btn:hover:not(:disabled) { border-color: #adb5bd; color: var(--cl-text); }
.cl-page-btn.active { background: var(--cl-red); border-color: var(--cl-red); color: #fff; }
.cl-page-btn:disabled { opacity: 0.4; cursor: default; }

/* ── Add Form ── */
.cl-add-form {
    background: #fff;
    border: 1px solid var(--cl-border);
    border-radius: 14px;
    padding: 1.5rem;
}
.cl-add-form .form-label { font-size: 0.8rem; font-weight: 600; color: var(--cl-text); margin-bottom: 0.3rem; }
.cl-add-form .form-control, .cl-add-form .form-select {
    border-color: var(--cl-border);
    border-radius: 8px;
    font-size: 0.875rem;
}
.cl-add-form .form-control:focus, .cl-add-form .form-select:focus {
    border-color: var(--cl-red);
    box-shadow: 0 0 0 3px rgba(232,25,44,0.08);
}

/* ── Modal overrides ── */
.modal-content { border-radius: 16px; border: none; }
.modal-header { border-bottom: 1px solid var(--cl-border); padding: 1.25rem 1.5rem; }
.modal-footer { border-top: 1px solid var(--cl-border); padding: 1rem 1.5rem; }
.modal-body { padding: 1.5rem; }
.modal-title { font-weight: 700; font-size: 1rem; }

/* ── Alerts ── */
.cl-alert {
    border-radius: 10px;
    border: none;
    font-size: 0.875rem;
    padding: 0.85rem 1.1rem;
}

/* ── Collapse hidden views with no leftover space ── */
#archivedView[style*="display: none"],
#activeView[style*="display: none"] {
    margin: 0 !important;
    padding: 0 !important;
    height: 0 !important;
    overflow: hidden !important;
}
</style>

<div class="container pb-4" id="customer">

    
    <div class="d-flex flex-row justify-content-between align-items-center pt-4">
        <div class="cl-page-header">
            <div class="cl-breadcrumb">Finance &bull; Customers</div>
            <h1>Customers</h1>
            <p>Manage customer information and credits</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn-cl-outline" id="archivedTabBtn" onclick="toggleView('archived')">
                <i class="bi bi-archive me-1"></i> Archived
                <?php if($archivedCustomers->total() > 0): ?>
                    <span class="badge ms-1" style="background:#f3f4f6;color:#374151;font-size:0.7rem;"><?php echo e($archivedCustomers->total()); ?></span>
                <?php endif; ?>
            </button>
            <button class="btn-cl-primary" id="showFormBtn" onclick="showForm()">
                <i class="bi bi-plus-lg me-1"></i> Add Customer
            </button>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert cl-alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert cl-alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <div class="cl-add-form mt-4" id="customerForm" style="display: none;">
        <div class="d-flex align-items-center gap-2 mb-4">
            <div style="width:36px;height:36px;border-radius:10px;background:var(--cl-red-light);display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-person-plus" style="color:var(--cl-red);font-size:1rem;"></i>
            </div>
            <h6 class="fw-bold mb-0" style="font-size:0.95rem;">Add New Customer</h6>
        </div>
        <form action="<?php echo e(route('customers.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="d-flex flex-wrap gap-3">
                <div class="flex-grow-1" style="min-width: 200px;">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?php $__errorArgs = ['fname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           name="fname" value="<?php echo e(old('fname')); ?>" placeholder="First Name">
                    <?php $__errorArgs = ['fname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="flex-grow-1" style="min-width: 200px;">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control <?php $__errorArgs = ['mname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           name="mname" value="<?php echo e(old('mname')); ?>" placeholder="Middle Name (optional)">
                    <?php $__errorArgs = ['mname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="flex-grow-1" style="min-width: 200px;">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?php $__errorArgs = ['lname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           name="lname" value="<?php echo e(old('lname')); ?>" placeholder="Last Name">
                    <?php $__errorArgs = ['lname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-3 mt-3">
                <div class="flex-grow-1" style="min-width: 200px;">
                    <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?php $__errorArgs = ['contact_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           name="contact_number" value="<?php echo e(old('contact_number')); ?>" placeholder="09XX XXX XXXX">
                    <?php $__errorArgs = ['contact_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="flex-grow-1" style="min-width: 200px;">
                    <label class="form-label">Address <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           name="address" value="<?php echo e(old('address')); ?>" placeholder="Address">
                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-end gap-2 pt-2" style="border-top:1px solid var(--cl-border);">
                <button type="button" class="btn-cl-outline" onclick="hideForm()">Cancel</button>
                <button type="submit" class="btn-cl-primary">
                    <i class="bi bi-person-plus me-1"></i>Add Customer
                </button>
            </div>
        </form>
    </div>


    
    
    
    <div id="activeView">
        
        <form method="GET" action="<?php echo e(route('customers')); ?>" class="mt-4" id="searchFilterForm">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                
                <div class="input-group cl-search-group" style="max-width: 340px;">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" id="searchInput"
                           class="form-control border-start-0 ps-0"
                           placeholder="Search name, contact, address…"
                           value="<?php echo e($search ?? ''); ?>"
                           autocomplete="off">
                    <?php if($search): ?>
                        <button type="button" class="btn btn-outline-secondary"
                                onclick="clearSearch()" title="Clear search" style="border-color:var(--cl-border);">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    <?php endif; ?>
                </div>

                
                <div class="d-flex gap-1">
                    <a href="<?php echo e(route('customers', array_merge(request()->except(['balance','page']), ['search' => $search]))); ?>"
                       class="cl-filter-pill <?php echo e(!($balance ?? null) ? 'active-all' : ''); ?>">
                        All
                    </a>
                    <a href="<?php echo e(route('customers', array_merge(request()->except(['balance','page']), ['search' => $search, 'balance' => 'with']))); ?>"
                       class="cl-filter-pill <?php echo e(($balance ?? null) === 'with' ? 'active-danger' : ''); ?>">
                        <i class="bi bi-exclamation-circle me-1"></i>With Balance
                    </a>
                    <a href="<?php echo e(route('customers', array_merge(request()->except(['balance','page']), ['search' => $search, 'balance' => 'without']))); ?>"
                       class="cl-filter-pill <?php echo e(($balance ?? null) === 'without' ? 'active-success' : ''); ?>">
                        <i class="bi bi-check-circle me-1"></i>No Balance
                    </a>
                </div>

                <button type="submit" class="btn-cl-primary" style="padding:0.45rem 1rem;">
                    <i class="bi bi-search me-1"></i>Search
                </button>
            </div>
        </form>

        <div class="cl-card mt-3" style="padding:0;overflow:hidden;">
            <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-bottom:1px solid var(--cl-border);">
                <span class="fw-bold" style="font-size:0.9rem;">
                    Active Customers
                    <?php if($search || ($balance ?? null)): ?>
                        <span class="text-muted fw-normal fs-6 ms-1">
                            — filtered
                            <?php if($search): ?><span class="cl-badge cl-badge-secondary ms-1"><?php echo e($search); ?></span><?php endif; ?>
                            <?php if(($balance ?? null) === 'with'): ?><span class="cl-badge cl-badge-danger ms-1">With Balance</span><?php endif; ?>
                            <?php if(($balance ?? null) === 'without'): ?><span class="cl-badge cl-badge-success ms-1">No Balance</span><?php endif; ?>
                        </span>
                    <?php endif; ?>
                </span>
                <span class="text-muted small">
                    Showing <?php echo e($customers->firstItem() ?? 0); ?>–<?php echo e($customers->lastItem() ?? 0); ?>

                    of <?php echo e($customers->total()); ?> customers
                </span>
            </div>

            <table class="cl-table">
                <thead>
                    <tr>
                        <th style="padding-left:1.5rem;">#</th>
                        <th>Customer</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        <th>Outstanding Balance</th>
                        <th>Status</th>
                        <th style="text-align:center;padding-right:1.5rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $outstanding = $customer->credits->sum(function ($c) {
                                $amount = round((float) $c->price_per_liter * (float) $c->Quantity, 2);
                                $paid   = $c->payments->sum('amount_paid') ?? 0;
                                return max(0, $amount - $paid);
                            });
                            $initials = strtoupper(substr($customer->First_name, 0, 1) . substr($customer->Last_name, 0, 1));
                        ?>
                        <tr>
                            <td style="padding-left:1.5rem;color:var(--cl-muted);font-size:0.8rem;"><?php echo e($customer->CustomerID); ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="cl-avatar"><?php echo e($initials); ?></div>
                                    <span class="fw-semibold" style="font-size:0.875rem;">
                                        <?php echo e($customer->First_name); ?>

                                        <?php echo e($customer->Middle_name ? $customer->Middle_name . ' ' : ''); ?><?php echo e($customer->Last_name); ?>

                                    </span>
                                </div>
                            </td>
                            <td style="color:var(--cl-muted);"><?php echo e($customer->contact_number); ?></td>
                            <td style="color:var(--cl-muted);max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo e($customer->address); ?></td>
                            <td>
                                <?php if($outstanding > 0): ?>
                                    <span style="color:#dc2626;font-weight:700;">₱<?php echo e(number_format($outstanding, 2)); ?></span>
                                <?php else: ?>
                                    <span style="color:var(--cl-green);font-weight:600;">₱<?php echo e(number_format($outstanding, 2)); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><span class="cl-badge cl-badge-success"><span style="width:6px;height:6px;border-radius:50%;background:var(--cl-green);display:inline-block;"></span> Active</span></td>
                            <td style="text-align:center;padding-right:1.5rem;">
                                <div class="dropdown">
                                    <button class="cl-action-btn" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical" style="font-size:0.8rem;"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius:10px;border:1px solid var(--cl-border);font-size:0.85rem;min-width:150px;">
                                        <li>
                                            <a class="dropdown-item py-2 view-btn" href="#"
                                               data-id="<?php echo e($customer->CustomerID); ?>"
                                               data-fname="<?php echo e($customer->First_name); ?>"
                                               data-mname="<?php echo e($customer->Middle_name); ?>"
                                               data-lname="<?php echo e($customer->Last_name); ?>"
                                               data-contact="<?php echo e($customer->contact_number); ?>"
                                               data-address="<?php echo e($customer->address); ?>">
                                                <i class="bi bi-eye me-2 text-muted"></i>View
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-2 edit-btn" href="#"
                                               data-id="<?php echo e($customer->CustomerID); ?>"
                                               data-fname="<?php echo e($customer->First_name); ?>"
                                               data-mname="<?php echo e($customer->Middle_name); ?>"
                                               data-lname="<?php echo e($customer->Last_name); ?>"
                                               data-contact="<?php echo e($customer->contact_number); ?>"
                                               data-address="<?php echo e($customer->address); ?>">
                                                <i class="bi bi-pencil me-2 text-muted"></i>Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <a class="dropdown-item py-2 text-warning archive-btn" href="#"
                                               data-id="<?php echo e($customer->CustomerID); ?>"
                                               data-name="<?php echo e($customer->First_name); ?> <?php echo e($customer->Last_name); ?>">
                                                <i class="bi bi-archive me-2"></i>Archive
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5" style="color:var(--cl-muted);">
                                <i class="bi bi-people fs-2 d-block mb-2 opacity-40"></i>
                                <span class="fw-semibold d-block mb-1">No active customers found</span>
                                <span class="small">Try adjusting your search or filter.</span>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if($customers->hasPages()): ?>
                <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid var(--cl-border);">
                    <span class="text-muted small">Page <?php echo e($customers->currentPage()); ?> of <?php echo e($customers->lastPage()); ?></span>
                    <div class="d-flex gap-1">
                        <?php if($customers->onFirstPage()): ?>
                            <button class="cl-page-btn" disabled><i class="bi bi-chevron-left"></i></button>
                        <?php else: ?>
                            <a href="<?php echo e($customers->previousPageUrl()); ?>" class="cl-page-btn"><i class="bi bi-chevron-left"></i></a>
                        <?php endif; ?>
                        <?php for($i = 1; $i <= $customers->lastPage(); $i++): ?>
                            <?php if($i == $customers->currentPage()): ?>
                                <span class="cl-page-btn active"><?php echo e($i); ?></span>
                            <?php else: ?>
                                <a href="<?php echo e($customers->url($i)); ?>" class="cl-page-btn"><?php echo e($i); ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <?php if($customers->hasMorePages()): ?>
                            <a href="<?php echo e($customers->nextPageUrl()); ?>" class="cl-page-btn"><i class="bi bi-chevron-right"></i></a>
                        <?php else: ?>
                            <button class="cl-page-btn" disabled><i class="bi bi-chevron-right"></i></button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>


    
    
    
    <div id="archivedView" style="display: none;">
        <div class="cl-card mt-4" style="padding:0;overflow:hidden;">
            <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-bottom:1px solid var(--cl-border);">
                <span class="fw-bold" style="font-size:0.9rem;">Archived Customers</span>
                <span class="text-muted small"><?php echo e($archivedCustomers->total()); ?> total archived</span>
            </div>
            <table class="cl-table">
                <thead>
                    <tr>
                        <th style="padding-left:1.5rem;">#</th>
                        <th>Customer</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th style="text-align:center;padding-right:1.5rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $archivedCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $initials = strtoupper(substr($customer->First_name, 0, 1) . substr($customer->Last_name, 0, 1)); ?>
                        <tr>
                            <td style="padding-left:1.5rem;color:var(--cl-muted);font-size:0.8rem;"><?php echo e($customer->CustomerID); ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="cl-avatar" style="background:#9ca3af;"><?php echo e($initials); ?></div>
                                    <span style="color:var(--cl-muted);font-size:0.875rem;">
                                        <?php echo e($customer->First_name); ?>

                                        <?php echo e($customer->Middle_name ? $customer->Middle_name . ' ' : ''); ?><?php echo e($customer->Last_name); ?>

                                    </span>
                                </div>
                            </td>
                            <td style="color:var(--cl-muted);"><?php echo e($customer->contact_number); ?></td>
                            <td style="color:var(--cl-muted);"><?php echo e($customer->address); ?></td>
                            <td><span class="cl-badge cl-badge-secondary">Archived</span></td>
                            <td style="text-align:center;padding-right:1.5rem;">
                                <div class="d-flex justify-content-center gap-2">
                                    <form action="<?php echo e(route('customers.archive', $customer->CustomerID)); ?>" method="POST">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-success" style="border-radius:8px;font-size:0.8rem;"
                                                onclick="return confirm('Restore <?php echo e(addslashes($customer->First_name . ' ' . $customer->Last_name)); ?>?')">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>Restore
                                        </button>
                                    </form>
                                    <form action="<?php echo e(route('customers.destroy', $customer->CustomerID)); ?>" method="POST">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-size:0.8rem;"
                                                onclick="return confirm('Permanently delete <?php echo e(addslashes($customer->First_name . ' ' . $customer->Last_name)); ?>? This cannot be undone.')">
                                            <i class="bi bi-trash me-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5" style="color:var(--cl-muted);">
                                <i class="bi bi-archive fs-2 d-block mb-2 opacity-40"></i>
                                <span class="fw-semibold d-block mb-1">No archived customers</span>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if($archivedCustomers->hasPages()): ?>
                <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid var(--cl-border);">
                    <span class="text-muted small">Page <?php echo e($archivedCustomers->currentPage()); ?> of <?php echo e($archivedCustomers->lastPage()); ?></span>
                    <div class="d-flex gap-1">
                        <?php if($archivedCustomers->onFirstPage()): ?>
                            <button class="cl-page-btn" disabled><i class="bi bi-chevron-left"></i></button>
                        <?php else: ?>
                            <a href="<?php echo e($archivedCustomers->previousPageUrl()); ?>&tab=archived" class="cl-page-btn"><i class="bi bi-chevron-left"></i></a>
                        <?php endif; ?>
                        <?php for($i = 1; $i <= $archivedCustomers->lastPage(); $i++): ?>
                            <?php if($i == $archivedCustomers->currentPage()): ?>
                                <span class="cl-page-btn active"><?php echo e($i); ?></span>
                            <?php else: ?>
                                <a href="<?php echo e($archivedCustomers->url($i)); ?>&tab=archived" class="cl-page-btn"><?php echo e($i); ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <?php if($archivedCustomers->hasMorePages()): ?>
                            <a href="<?php echo e($archivedCustomers->nextPageUrl()); ?>&tab=archived" class="cl-page-btn"><i class="bi bi-chevron-right"></i></a>
                        <?php else: ?>
                            <button class="cl-page-btn" disabled><i class="bi bi-chevron-right"></i></button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>





<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--cl-bg);">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:40px;height:40px;border-radius:10px;background:var(--cl-red-light);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-person" style="color:var(--cl-red);font-size:1.1rem;"></i>
                    </div>
                    <h5 class="modal-title mb-0">Customer Details</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div style="background:var(--cl-bg);border-radius:10px;padding:1rem;">
                            <div style="font-size:0.7rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:var(--cl-muted);margin-bottom:0.3rem;">Full Name</div>
                            <div id="view_name" class="fw-bold" style="font-size:0.95rem;"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="background:var(--cl-bg);border-radius:10px;padding:1rem;">
                            <div style="font-size:0.7rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:var(--cl-muted);margin-bottom:0.3rem;">Contact Number</div>
                            <div id="view_contact" class="fw-semibold" style="font-size:0.95rem;"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="background:var(--cl-bg);border-radius:10px;padding:1rem;">
                            <div style="font-size:0.7rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:var(--cl-muted);margin-bottom:0.3rem;">Address</div>
                            <div id="view_address" class="fw-semibold" style="font-size:0.95rem;"></div>
                        </div>
                    </div>
                </div>

                <hr style="border-color:var(--cl-border);">

                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0" style="font-size:0.9rem;">Credit History</h6>
                    <button class="btn-cl-primary" style="font-size:0.8rem;padding:0.4rem 0.9rem;" id="showCreditBtn" onclick="showCreditForm()">
                        <i class="bi bi-plus-lg me-1"></i> Add Credit
                    </button>
                </div>

                
                <div style="display:none;" id="addCreditForm">
                    <form action="<?php echo e(route('credits.store')); ?>" method="POST" class="border rounded-3 p-3 mb-3 bg-light">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="customer_id" id="credit_customer_id">
                        
                        <input type="hidden" name="price_per_liter" id="credit_price_hidden">
                        <input type="hidden" name="discount_per_liter" id="credit_discount_hidden">

                        <div class="row g-3">
                            
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="credit_date" id="credit_date">
                            </div>

                            
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Fuel Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="pump_fuel_id" id="credit_fuel_id">
                                    <option value="">— Select pump fuel —</option>
                                    <?php if(isset($fuels)): ?>
                                        <?php $__currentLoopData = $fuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pumpFuel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($pumpFuel->PumpFuelID); ?>"
                                                    data-price="<?php echo e($pumpFuel->price_per_liter); ?>"
                                                    data-name="<?php echo e($pumpFuel->fuel->fuel_name ?? ''); ?>">
                                                <?php echo e($pumpFuel->pump->pump_name ?? 'Pump'); ?> — <?php echo e($pumpFuel->fuel->fuel_name ?? '—'); ?> (₱<?php echo e(number_format($pumpFuel->price_per_liter, 2)); ?>/L)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    Price / L
                                    <span class="text-muted fw-normal small" id="credit_pump_price_label"></span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" step="0.01" min="0" class="form-control"
                                           name="price_per_liter" id="credit_price_per_liter"
                                           placeholder="0.00">
                                </div>
                                <div class="form-text text-muted" id="credit_price_hint" style="display:none;">
                                    <i class="bi bi-info-circle me-1"></i>Auto-filled from pump price. You can edit it.
                                </div>
                            </div>

                            
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Discount / L <span class="text-muted fw-normal small">(optional)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" step="0.01" min="0" class="form-control"
                                           name="discount_per_liter" id="credit_discount"
                                           placeholder="0.00" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Liters <span class="text-danger">*</span></label>
                                <input type="number" step="0.001" min="0" class="form-control"
                                       name="Quantity" id="credit_liters" placeholder="0.000">
                            </div>

                            
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Total Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="text" class="form-control bg-white fw-semibold text-primary"
                                           id="credit_total_amount" placeholder="0.00" readonly>
                                </div>
                            </div>

                            
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Total Discount</label>
                                <div class="input-group">
                                    <span class="input-group-text text-success">₱</span>
                                    <input type="text" class="form-control bg-white fw-semibold text-success"
                                           id="credit_total_discount" placeholder="0.00" readonly>
                                </div>
                            </div>
                        </div>

                        
                        <div id="credit_calc_preview" class="mt-3 p-2 rounded border bg-white small text-muted" style="display:none;">
                            <i class="bi bi-calculator me-1"></i>
                            <span id="credit_calc_text"></span>
                        </div>

                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="hideCreditForm()">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm" id="credit_submit_btn" disabled>
                                <i class="bi bi-plus-lg me-1"></i>Add Credit
                            </button>
                        </div>
                    </form>
                </div>

                
                <div id="creditLoadingSpinner" class="text-center py-3" style="display:none;">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    <span class="ms-2 text-muted small">Loading credits…</span>
                </div>

                
                <div class="table-responsive">
                    <table class="table table-sm table-hover" id="creditHistoryTable">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Fuel Type</th>
                                <th>Liters</th>
                                <th>Price / L</th>
                                <th>Amount</th>
                                <th>Paid</th>
                                <th>Remaining</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="creditHistoryBody">
                            <tr>
                                <td colspan="9" class="text-center text-muted py-3">No credit records yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                
                <div class="d-flex justify-content-end gap-4 mt-1 small">
                    <span class="text-muted">Total Credits: <span class="fw-semibold text-dark" id="creditTotalAmount">₱0.00</span></span>
                    <span class="text-muted">Total Paid: <span class="fw-semibold text-success" id="creditTotalPaid">₱0.00</span></span>
                    <span class="text-muted">Outstanding: <span class="fw-semibold text-danger" id="creditTotalBalance">₱0.00</span></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cl-outline" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>





<div class="modal fade" id="creditDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-receipt me-2"></i>Credit Detail
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="text-muted small mb-1">Date</div>
                            <div class="fw-semibold" id="det_date">—</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="text-muted small mb-1">Fuel Type</div>
                            <div class="fw-semibold" id="det_fuel">—</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="text-muted small mb-1">Liters × Price</div>
                            <div class="fw-semibold" id="det_liters_price">—</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="text-muted small mb-1">Total Amount</div>
                            <div class="fw-semibold text-primary" id="det_amount">—</div>
                        </div>
                    </div>
                </div>

                
                <div class="mb-4">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-success">Paid: <strong id="det_paid">₱0.00</strong></span>
                        <span class="text-danger">Remaining: <strong id="det_remaining">₱0.00</strong></span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" id="det_progress" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>

                
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="text-muted small">Payment Status:</span>
                    <span id="det_status_badge"></span>
                    <button class="btn btn-sm btn-outline-secondary ms-auto" type="button"
                            data-bs-toggle="collapse" data-bs-target="#changeStatusForm">
                        <i class="bi bi-pencil me-1"></i>Change Status
                    </button>
                </div>

                
                
                <div class="collapse mb-3" id="changeStatusForm">
                    <form method="POST" id="statusForm" class="border rounded p-3 bg-light d-flex align-items-end gap-3">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <div class="flex-grow-1">
                            <label class="form-label small mb-1">New Status</label>
                            <select class="form-select form-select-sm" name="status" id="statusSelect">
                                <option value="unpaid">Unpaid</option>
                                <option value="partial">Partial</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                    </form>
                </div>

                <hr>

                
                <div class="mb-3" id="addPaymentSection">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-semibold mb-0">Payment History</h6>
                        <button class="btn btn-sm btn-success" type="button"
                                data-bs-toggle="collapse" data-bs-target="#addPaymentForm"
                                id="addPaymentToggle">
                            <i class="bi bi-cash-coin me-1"></i>Add Payment
                        </button>
                    </div>

                    <div class="collapse mb-3" id="addPaymentForm">
                        
                        <form method="POST" id="paymentForm" action="#" class="border rounded p-3 bg-light">
                            <?php echo csrf_field(); ?>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Payment Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control form-control-sm"
                                           name="payment_date" id="pay_date" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">
                                        Amount Paid
                                        <span class="text-muted fw-normal">(max: <span id="pay_max_label">₱0.00</span>)</span>
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" step="0.01" min="0.01"
                                               class="form-control"
                                               name="amount_paid" id="pay_amount"
                                               placeholder="0.00" required>
                                    </div>
                                    <div class="form-text" id="pay_amount_hint"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">
                                        Note
                                        <span class="text-muted fw-normal">(optional)</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                           name="note" id="pay_note" placeholder="e.g. downpayment, GCash">
                                </div>
                            </div>
                            <div class="mt-2 d-flex gap-2 align-items-center">
                                <span class="text-muted small">Quick fill:</span>
                                <button type="button" class="btn btn-outline-secondary btn-sm py-0"
                                        onclick="document.getElementById('pay_amount').value = document.getElementById('pay_amount').max; updatePayAmountHint();">
                                    Full balance
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm py-0"
                                        onclick="document.getElementById('pay_amount').value = (parseFloat(document.getElementById('pay_amount').max)/2).toFixed(2); updatePayAmountHint();">
                                    Half
                                </button>
                            </div>
                            <div class="mt-3 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-toggle="collapse" data-bs-target="#addPaymentForm">Cancel</button>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-check-lg me-1"></i>Record Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                
                <div id="paymentLoadingSpinner" class="text-center py-2" style="display:none;">
                    <div class="spinner-border spinner-border-sm" style="color:var(--cl-red);" role="status"></div>
                </div>

                
                <table class="cl-table" style="margin-top:0.5rem;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Amount Paid</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody id="paymentHistoryBody">
                        <tr>
                            <td colspan="4" class="text-center py-3" style="color:var(--cl-muted);">No payments yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cl-outline" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>





<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--cl-bg);">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:36px;height:36px;border-radius:10px;background:var(--cl-red-light);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-pencil" style="color:var(--cl-red);"></i>
                    </div>
                    <h5 class="modal-title mb-0">Edit Customer</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:0.8rem;font-weight:600;">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_fname" name="fname" required style="border-radius:8px;border-color:var(--cl-border);">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:0.8rem;font-weight:600;">Middle Name</label>
                        <input type="text" class="form-control" id="edit_mname" name="mname" style="border-radius:8px;border-color:var(--cl-border);">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:0.8rem;font-weight:600;">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_lname" name="lname" required style="border-radius:8px;border-color:var(--cl-border);">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:0.8rem;font-weight:600;">Contact Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_contact" name="contact_number" required style="border-radius:8px;border-color:var(--cl-border);">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:0.8rem;font-weight:600;">Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_address" name="address" required style="border-radius:8px;border-color:var(--cl-border);">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cl-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-cl-primary"
                        onclick="document.getElementById('editForm').submit()">Save Changes</button>
            </div>
        </div>
    </div>
</div>





<div class="modal fade" id="archiveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center px-4 pb-2">
                <div style="width:56px;height:56px;border-radius:14px;background:#fffbeb;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <i class="bi bi-archive" style="font-size:1.5rem;color:#d97706;"></i>
                </div>
                <h6 class="fw-bold mb-1">Archive Customer?</h6>
                <p class="fw-semibold mb-1" id="archiveName" style="color:var(--cl-text);"></p>
                <p class="text-muted small mb-0">They'll be hidden from active list and moved to Archived.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-2">
                <button type="button" class="btn-cl-outline" data-bs-dismiss="modal">Cancel</button>
                <form id="archiveForm" method="POST" style="display:inline;">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn btn-warning" style="border-radius:8px;font-weight:600;font-size:0.85rem;">
                        <i class="bi bi-archive me-1"></i>Archive
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>





<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Helpers ──────────────────────────────────────────────────────────────
    const fmt = n => '₱' + parseFloat(n || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    // FIX: key is 'payment_status' in JS because that's what our JSON now returns
    const statusBadge = s => ({
        unpaid:  '<span class="cl-badge cl-badge-danger"><span style="width:6px;height:6px;border-radius:50%;background:#dc2626;display:inline-block;"></span> Unpaid</span>',
        partial: '<span class="cl-badge" style="background:#fef3c7;color:#92400e;"><span style="width:6px;height:6px;border-radius:50%;background:#d97706;display:inline-block;"></span> Partial</span>',
        paid:    '<span class="cl-badge cl-badge-success"><span style="width:6px;height:6px;border-radius:50%;background:#10b981;display:inline-block;"></span> Paid</span>',
    }[s] || '<span class="cl-badge cl-badge-secondary">Unknown</span>');

    const fmtDate = d => {
        if (!d) return '—';
        const dt = new Date(d);
        if (isNaN(dt)) return d;
        return dt.toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' });
    };

    // ── Tab: Active / Archived ────────────────────────────────────────────────
    const activeView   = document.getElementById('activeView');
    const archivedView = document.getElementById('archivedView');
    const archivedBtn  = document.getElementById('archivedTabBtn');
    const showFormBtn  = document.getElementById('showFormBtn');
    const customerForm = document.getElementById('customerForm');

    if (new URLSearchParams(window.location.search).get('tab') === 'archived') applyArchivedView();

    window.toggleView = tab => {
        if (tab === 'archived') { applyArchivedView(); history.replaceState(null, '', '?tab=archived'); }
        else                    { applyActiveView();   history.replaceState(null, '', window.location.pathname); }
    };

    function applyArchivedView() {
        activeView.style.display = 'none'; archivedView.style.display = 'block';
        archivedView.style.marginTop = '1rem';
        customerForm.style.display = 'none'; showFormBtn.style.display = 'none';
        archivedBtn.innerHTML = '<i class="bi bi-people me-1"></i> Active Customers';
        archivedBtn.onclick = () => toggleView('active');
    }
    function applyActiveView() {
        activeView.style.display = 'block'; archivedView.style.display = 'none';
        archivedView.style.marginTop = '0';
        showFormBtn.style.display = 'inline-block';
        archivedBtn.innerHTML = '<i class="bi bi-archive me-1"></i> Archived'
            + (<?php echo json_encode($archivedCustomers->total(), 15, 512) ?> > 0
                ? ' <span class="badge bg-secondary ms-1">' + <?php echo json_encode($archivedCustomers->total(), 15, 512) ?> + '</span>' : '');
        archivedBtn.onclick = () => toggleView('archived');
    }

    // ── Add Customer Form ────────────────────────────────────────────────────
    window.showForm = () => { customerForm.style.display = 'block'; showFormBtn.style.display = 'none'; customerForm.scrollIntoView({ behavior: 'smooth' }); };
    window.hideForm = () => { customerForm.style.display = 'none'; showFormBtn.style.display = 'inline-block'; };
    <?php if($errors->any()): ?> showForm(); <?php endif; ?>

    // ── View Modal ───────────────────────────────────────────────────────────
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const mname = this.dataset.mname ? this.dataset.mname + ' ' : '';
            document.getElementById('view_name').textContent    = this.dataset.fname + ' ' + mname + this.dataset.lname;
            document.getElementById('view_contact').textContent = this.dataset.contact;
            document.getElementById('view_address').textContent = this.dataset.address;
            const cid = this.dataset.id;
            document.getElementById('credit_customer_id').value = cid;
            resetCreditForm();
            hideCreditForm();
            loadCreditHistory(cid);
            new bootstrap.Modal(document.getElementById('viewModal')).show();
        });
    });

    // ── Edit Modal ───────────────────────────────────────────────────────────
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('edit_fname').value   = this.dataset.fname;
            document.getElementById('edit_mname').value   = this.dataset.mname;
            document.getElementById('edit_lname').value   = this.dataset.lname;
            document.getElementById('edit_contact').value = this.dataset.contact;
            document.getElementById('edit_address').value = this.dataset.address;
            document.getElementById('editForm').action    = '/customers/' + this.dataset.id;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        });
    });

    // ── Archive Modal ────────────────────────────────────────────────────────
    document.querySelectorAll('.archive-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('archiveName').textContent = this.dataset.name;
            document.getElementById('archiveForm').action      = '/customers/' + this.dataset.id + '/archive';
            new bootstrap.Modal(document.getElementById('archiveModal')).show();
        });
    });

    // ── Credit Form show/hide ────────────────────────────────────────────────
    window.showCreditForm = () => {
        document.getElementById('addCreditForm').style.display = 'block';
        document.getElementById('showCreditBtn').style.display = 'none';
    };
    window.hideCreditForm = () => {
        document.getElementById('addCreditForm').style.display = 'none';
        document.getElementById('showCreditBtn').style.display = 'inline-block';
    };

    function resetCreditForm() {
        document.getElementById('credit_liters').value          = '';
        document.getElementById('credit_price_per_liter').value = '';
        document.getElementById('credit_discount').value        = '0';
        document.getElementById('credit_total_amount').value    = '';
        document.getElementById('credit_total_discount').value  = '';
        document.getElementById('credit_date').value            = new Date().toISOString().split('T')[0];
        document.getElementById('credit_fuel_id').value         = '';
        document.getElementById('credit_calc_preview').style.display = 'none';
        document.getElementById('credit_price_hint').style.display   = 'none';
        document.getElementById('credit_pump_price_label').textContent = '';
        document.getElementById('credit_submit_btn').disabled = true;
        document.getElementById('credit_price_hidden').value    = '';
        document.getElementById('credit_discount_hidden').value = '';
    }

    // ── Credit Form: auto-fill price when fuel changes ───────────────────────
    document.getElementById('credit_fuel_id').addEventListener('change', function () {
        const opt   = this.options[this.selectedIndex];
        const price = parseFloat(opt.dataset.price) || 0;

        if (price > 0) {
            document.getElementById('credit_price_per_liter').value = price.toFixed(2);
            document.getElementById('credit_pump_price_label').textContent = '(pump: ₱' + price.toFixed(2) + ')';
            document.getElementById('credit_price_hint').style.display = 'block';
        } else {
            document.getElementById('credit_price_per_liter').value = '';
            document.getElementById('credit_pump_price_label').textContent = '';
            document.getElementById('credit_price_hint').style.display = 'none';
        }
        calcCredit();
    });

    // ── Credit Form: recalculate on any input change ─────────────────────────
    ['credit_liters', 'credit_price_per_liter', 'credit_discount'].forEach(id => {
        document.getElementById(id).addEventListener('input', calcCredit);
    });

    function calcCredit() {
        const liters   = parseFloat(document.getElementById('credit_liters').value)          || 0;
        const price    = parseFloat(document.getElementById('credit_price_per_liter').value) || 0;
        const discount = parseFloat(document.getElementById('credit_discount').value)         || 0;

        const grossAmt  = liters * price;
        const totalDisc = liters * discount;
        const netAmt    = Math.max(0, grossAmt - totalDisc);

        const totalAmtEl  = document.getElementById('credit_total_amount');
        const totalDiscEl = document.getElementById('credit_total_discount');
        const preview     = document.getElementById('credit_calc_preview');
        const previewText = document.getElementById('credit_calc_text');
        const submitBtn   = document.getElementById('credit_submit_btn');

        if (liters > 0 && price > 0) {
            totalAmtEl.value  = netAmt.toFixed(2);
            totalDiscEl.value = totalDisc.toFixed(2);

            let calcStr = liters.toFixed(3) + 'L × ₱' + price.toFixed(2) + ' = ₱' + grossAmt.toFixed(2);
            if (discount > 0) {
                calcStr += ' − discount ₱' + totalDisc.toFixed(2)
                         + ' (₱' + discount.toFixed(2) + '/L)'
                         + ' = <strong class="text-primary">₱' + netAmt.toFixed(2) + '</strong>';
            } else {
                calcStr += ' = <strong class="text-primary">₱' + netAmt.toFixed(2) + '</strong>';
            }
            previewText.innerHTML = calcStr;
            preview.style.display = 'block';
            submitBtn.disabled    = false;

            // Keep hidden inputs in sync so the controller receives the values
            document.getElementById('credit_price_hidden').value   = price.toFixed(2);
            document.getElementById('credit_discount_hidden').value = discount.toFixed(2);
        } else {
            totalAmtEl.value  = '';
            totalDiscEl.value = '';
            preview.style.display = 'none';
            submitBtn.disabled    = true;
        }
    }

    // ── Payment form: amount hint ─────────────────────────────────────────────
    window.updatePayAmountHint = function () {
        const max  = parseFloat(document.getElementById('pay_amount').max)   || 0;
        const val  = parseFloat(document.getElementById('pay_amount').value) || 0;
        const hint = document.getElementById('pay_amount_hint');
        if (val > max && max > 0) {
            hint.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>Exceeds remaining balance.</span>';
        } else if (val > 0) {
            const after = max - val;
            hint.innerHTML = '<span class="text-muted">Remaining after: <strong>₱' + after.toFixed(2) + '</strong></span>';
        } else {
            hint.innerHTML = '';
        }
    };
    document.getElementById('pay_amount').addEventListener('input', updatePayAmountHint);

    // ── Payment form: guard against missing action & overpayment ─────────────
    document.getElementById('paymentForm').addEventListener('submit', function (e) {
        const action = this.action;
        if (!action || action === '#' || action.endsWith('#')) {
            e.preventDefault();
            alert('Unable to submit: no credit selected. Please close and reopen the credit detail.');
            return false;
        }
        const amount = parseFloat(document.getElementById('pay_amount').value) || 0;
        const max    = parseFloat(document.getElementById('pay_amount').max)   || 0;
        if (amount <= 0) {
            e.preventDefault();
            alert('Please enter a valid payment amount greater than 0.');
            return false;
        }
        if (max > 0 && amount > max + 0.001) {
            e.preventDefault();
            alert('Amount exceeds remaining balance of ₱' + max.toFixed(2) + '.');
            return false;
        }
    });

    // ── Load Credit History ──────────────────────────────────────────────────
    function loadCreditHistory(customerId) {
        const tbody   = document.getElementById('creditHistoryBody');
        const spinner = document.getElementById('creditLoadingSpinner');

        spinner.style.display = 'block';
        tbody.innerHTML = '';

        fetch(`/customers/${customerId}/credits`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
        .then(credits => {
            spinner.style.display = 'none';

            if (!credits.length) {
                tbody.innerHTML = `<tr><td colspan="9" class="text-center text-muted py-3">No credit records yet.</td></tr>`;
                document.getElementById('creditTotalAmount').textContent  = '₱0.00';
                document.getElementById('creditTotalPaid').textContent    = '₱0.00';
                document.getElementById('creditTotalBalance').textContent = '₱0.00';
                return;
            }

            let totAmt = 0, totPaid = 0, totRemain = 0;

            tbody.innerHTML = credits.map(c => {
                totAmt    += parseFloat(c.amount)            || 0;
                totPaid   += parseFloat(c.amount_paid)       || 0;
                totRemain += parseFloat(c.remaining_balance) || 0;

                return `
                <tr style="cursor:pointer;" onclick="openCreditDetail(${c.id})" title="Click to view details">
                    <td>${fmtDate(c.date)}</td>
                    <td>${c.fuel_type}</td>
                    <td>${parseFloat(c.liters).toFixed(3)} L</td>
                    <td>${fmt(c.price)}</td>
                    <td>${fmt(c.amount)}</td>
                    <td class="text-success">${fmt(c.amount_paid)}</td>
                    <td class="${parseFloat(c.remaining_balance) > 0 ? 'text-danger fw-semibold' : 'text-success'}">${fmt(c.remaining_balance)}</td>
                    <td>${statusBadge(c.payment_status)}</td>
                    <td class="text-center">
                        <button class="btn btn-xs btn-outline-primary btn-sm py-0 px-2"
                                onclick="event.stopPropagation(); openCreditDetail(${c.id})">
                            <i class="bi bi-eye"></i>
                        </button>
                    </td>
                </tr>`;
            }).join('');

            document.getElementById('creditTotalAmount').textContent  = fmt(totAmt);
            document.getElementById('creditTotalPaid').textContent    = fmt(totPaid);
            document.getElementById('creditTotalBalance').textContent = fmt(totRemain);
        })
        .catch(() => {
            spinner.style.display = 'none';
            tbody.innerHTML = `<tr><td colspan="9" class="text-center text-danger py-3"><i class="bi bi-exclamation-triangle me-1"></i>Failed to load. Please try again.</td></tr>`;
        });
    }

    // ── Open Credit Detail Modal ─────────────────────────────────────────────
    window.openCreditDetail = function (creditId) {
        const spinner = document.getElementById('paymentLoadingSpinner');
        spinner.style.display = 'block';
        document.getElementById('paymentHistoryBody').innerHTML = '';

        fetch(`/credits/${creditId}/detail`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
        .then(c => {
            spinner.style.display = 'none';

            // Summary cards
            document.getElementById('det_date').textContent         = fmtDate(c.date);
            document.getElementById('det_fuel').textContent         = c.fuel_type;
            document.getElementById('det_liters_price').textContent = parseFloat(c.liters).toFixed(3) + 'L × ' + fmt(c.price);
            document.getElementById('det_amount').textContent       = fmt(c.amount);
            document.getElementById('det_paid').textContent         = fmt(c.amount_paid);
            document.getElementById('det_remaining').textContent    = fmt(c.remaining_balance);
            document.getElementById('det_status_badge').innerHTML   = statusBadge(c.payment_status);

            // Progress bar
            const pct = c.amount > 0 ? Math.min(100, (c.amount_paid / c.amount) * 100) : 0;
            document.getElementById('det_progress').style.width = pct.toFixed(1) + '%';

            // Status form — FIX: route uses /credits/{id}/status
            document.getElementById('statusForm').action   = `/credits/${c.id}/status`;
            document.getElementById('statusSelect').value  = c.payment_status;

            // Payment form
            document.getElementById('paymentForm').action  = `/credits/${c.id}/pay`;
            document.getElementById('pay_date').value      = new Date().toISOString().split('T')[0];
            document.getElementById('pay_amount').max      = c.remaining_balance;
            document.getElementById('pay_max_label').textContent = fmt(c.remaining_balance);

            // Hide add payment if fully paid
            document.getElementById('addPaymentSection').style.display =
                c.payment_status === 'paid' ? 'none' : 'block';

            // Collapse payment form if visible
            const addPF = document.getElementById('addPaymentForm');
            if (addPF.classList.contains('show')) {
                new bootstrap.Collapse(addPF, { toggle: false }).hide();
            }
            // Collapse status form if visible
            const changeStatusF = document.getElementById('changeStatusForm');
            if (changeStatusF.classList.contains('show')) {
                new bootstrap.Collapse(changeStatusF, { toggle: false }).hide();
            }

            // Payment history table
            const pbody = document.getElementById('paymentHistoryBody');
            if (!c.payments || !c.payments.length) {
                pbody.innerHTML = `<tr><td colspan="4" class="text-center text-muted py-3">No payments recorded yet.</td></tr>`;
            } else {
                pbody.innerHTML = c.payments.map((p, i) => `
                    <tr>
                        <td class="text-muted small">${i + 1}</td>
                        <td>${fmtDate(p.payment_date)}</td>
                        <td class="text-success fw-semibold">${fmt(p.amount_paid)}</td>
                        <td class="text-muted">${p.note ? p.note : '—'}</td>
                    </tr>`).join('');
            }

            // Open the modal (stack on top of viewModal)
            new bootstrap.Modal(document.getElementById('creditDetailModal')).show();
        })
        .catch(() => {
            spinner.style.display = 'none';
            alert('Failed to load credit details. Please try again.');
        });
    };

    // ── Search: clear input and submit ──────────────────────────────────────
    window.clearSearch = function () {
        document.getElementById('searchInput').value = '';
        document.getElementById('searchFilterForm').submit();
    };

    // ── Re-open modal after redirect (open_customer param) ──────────────────
    const urlParams    = new URLSearchParams(window.location.search);
    const openCustomer = urlParams.get('open_customer');
    if (openCustomer) {
        const viewBtn = document.querySelector(`.view-btn[data-id="${openCustomer}"]`);
        if (viewBtn) viewBtn.click();
        history.replaceState(null, '', window.location.pathname);
    }

});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\john mark bolanon\laravel\Seal-gasolineStation\resources\views/customers/index.blade.php ENDPATH**/ ?>