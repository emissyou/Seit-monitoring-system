

<?php $__env->startSection('title', 'Shift Management'); ?>
<?php $__env->startSection('subtitle', 'Manage daily shift operations'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    :root {
        --fuel-premium: #f59e0b;
        --fuel-diesel:  #ef4444;
        --fuel-regular: #10b981;
    }

    .shift-nav .nav-link {
        font-weight: 600;
        padding: 0.85rem 1.5rem;
        border-bottom: 3px solid transparent;
        color: #64748b;
    }
    .shift-nav .nav-link.active {
        color: #dc2626;
        border-bottom-color: #dc2626;
    }

    .pump-card {
        transition: all 0.25s ease;
    }
    .pump-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 25px -5px rgb(0 0 0 / 0.1);
    }

    .fuel-badge {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.35em 0.75em;
        border-radius: 9999px;
    }

    .stat-value {
        font-size: 1.85rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
        color: #64748b;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<!-- Navigation Tabs -->
<ul class="nav nav-tabs shift-nav mb-4">
    <li class="nav-item">
        <a class="nav-link <?php echo e($view === 'home' ? 'active' : ''); ?>"
           href="<?php echo e(route('shift.management', ['view' => 'home'])); ?>">
            <i class="bi bi-house-door me-1"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo e($view === 'open' ? 'active' : ''); ?>"
           href="<?php echo e(route('shift.management', ['view' => 'open'])); ?>">
            <i class="bi bi-play-circle me-1"></i> Open Shift
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo e($view === 'close' ? 'active' : ''); ?>"
           href="<?php echo e(route('shift.management', ['view' => 'close'])); ?>">
            <i class="bi bi-stop-circle me-1"></i> Close Shift
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo e($view === 'archive' ? 'active' : ''); ?>"
           href="<?php echo e(route('shift.management', ['view' => 'archive'])); ?>">
            <i class="bi bi-archive me-1"></i> Archive
        </a>
    </li>
</ul>


<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i> <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i> <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>


<?php if($view === 'home'): ?>
    <?php echo $__env->make('Shift.partials.home', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>


<?php if($view === 'open'): ?>
    <?php echo $__env->make('Shift.partials.open', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>


<?php if($view === 'close'): ?>
    <?php echo $__env->make('Shift.partials.close', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>


<?php if($view === 'archive'): ?>
    <?php echo $__env->make('Shift.partials.archive', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// ==================== ACTION HELPERS ====================
function archiveShift(id) {
    if (confirm('Archive this shift?')) submitForm('/shift/' + id + '/archive', 'PATCH');
}
function restoreShift(id) {
    if (confirm('Restore this shift?')) submitForm('/shift/' + id + '/restore', 'PATCH');
}
function deleteShift(id) {
    if (confirm('Permanently delete this shift? This cannot be undone.')) submitForm('/shift/' + id, 'DELETE');
}
function cancelOpenShift(id) {
    if (confirm('Cancel this open shift?')) submitForm('/shift/' + id, 'DELETE');
}

function submitForm(url, method) {
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = url;

    var csrf = document.createElement('input');
    csrf.type  = 'hidden';
    csrf.name  = '_token';
    csrf.value = document.querySelector('meta[name="csrf-token"]').content;

    var methodInput = document.createElement('input');
    methodInput.type  = 'hidden';
    methodInput.name  = '_method';
    methodInput.value = method;

    form.appendChild(csrf);
    form.appendChild(methodInput);
    document.body.appendChild(form);
    form.submit();
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\john mark bolanon\laravel\Seal-gasolineStation\resources\views/Shift/index.blade.php ENDPATH**/ ?>