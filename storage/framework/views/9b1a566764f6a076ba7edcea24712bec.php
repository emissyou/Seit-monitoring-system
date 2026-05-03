

<?php $__env->startSection('title', 'Credit Logs'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .table th { font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.6px; color: #64748b; font-weight: 600; }
    .badge-unpaid  { background: #fee2e2; color: #991b1b; }
    .badge-partial { background: #fef3c7; color: #92400e; }
    .badge-paid    { background: #d1fae5; color: #065f46; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Credit Logs</h4>
        <p class="text-muted mb-0">Manage customer credits</p>
    </div>
    <a href="<?php echo e(route('credits.index')); ?>" class="btn btn-outline-secondary">All Credits</a>
</div>

<!-- Credits Table -->
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Fuel Type</th>
                        <th class="text-end">Quantity (L)</th>
                        <th class="text-end">Amount Paid</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $credits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $credit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            
                            <td><?php echo e($credit->credit_date?->format('M d, Y')); ?></td>

                            
                            <td><?php echo e($credit->customer->First_name ?? ''); ?> <?php echo e($credit->customer->Last_name ?? ''); ?></td>

                            
                            <td><?php echo e($credit->fuel->fuel_name ?? '—'); ?></td>

                            
                            <td class="text-end"><?php echo e(number_format($credit->Quantity, 3)); ?></td>

                            
                            <td class="text-end text-success">
                                ₱<?php echo e(number_format($credit->payments->sum('amount_paid'), 2)); ?>

                            </td>

                            
                            <?php
                                $totalPaid  = $credit->payments->sum('amount_paid');
                                // Credit has no monetary 'amount'; display payment count as status indicator
                                $status = $totalPaid <= 0 ? 'unpaid' : 'partial';
                                // Mark paid only when the attendant explicitly marks it (extend model as needed)
                            ?>
                            <td>
                                <span class="badge badge-<?php echo e($status); ?>">
                                    <?php echo e(ucfirst($status)); ?>

                                </span>
                            </td>

                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            
                                            <a class="dropdown-item" href="#" onclick="viewCreditDetail(<?php echo e($credit->CreditID); ?>)">
                                                <i class="bi bi-eye me-2"></i> View Detail
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-warning" href="#"
                                               onclick="archiveCredit(<?php echo e($credit->CreditID); ?>)">
                                                <i class="bi bi-archive me-2"></i> Archive
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">No credit records found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <?php echo e($credits->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function viewCreditDetail(id) {
    fetch(`/credits/${id}/detail`)
        .then(r => r.json())
        .then(data => {
            // Field names now match the Credit model
            let html = `Credit Detail\n\nDate: ${data.credit_date}\nFuel: ${data.fuel_name}\nQuantity: ${parseFloat(data.Quantity).toFixed(3)} L\nAmount Paid: ₱${parseFloat(data.amount_paid).toFixed(2)}`;
            alert(html);
        });
}

function archiveCredit(id) {
    if (confirm('Archive this credit record?')) {
        fetch(`/credits/${id}/archive`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Failed to archive credit');
            }
        });
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\john mark bolanon\laravel\Seal-gasolineStation\resources\views/credits/index.blade.php ENDPATH**/ ?>