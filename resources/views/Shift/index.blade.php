@extends('layouts.app')

@section('title', 'Shift Management')
@section('subtitle', 'Manage daily shift operations')

@push('styles')
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
@endpush

@section('content')

<!-- Navigation Tabs -->
<ul class="nav nav-tabs shift-nav mb-4">
    <li class="nav-item">
        <a class="nav-link {{ $view === 'home' ? 'active' : '' }}"
           href="{{ route('shift.management', ['view' => 'home']) }}">
            <i class="bi bi-house-door me-1"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $view === 'open' ? 'active' : '' }}"
           href="{{ route('shift.management', ['view' => 'open']) }}">
            <i class="bi bi-play-circle me-1"></i> Open Shift
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $view === 'close' ? 'active' : '' }}"
           href="{{ route('shift.management', ['view' => 'close']) }}">
            <i class="bi bi-stop-circle me-1"></i> Close Shift
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $view === 'archive' ? 'active' : '' }}"
           href="{{ route('shift.management', ['view' => 'archive']) }}">
            <i class="bi bi-archive me-1"></i> Archive
        </a>
    </li>
</ul>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- HOME VIEW --}}
@if($view === 'home')
    @include('Shift.partials.home')
@endif

{{-- OPEN SHIFT VIEW --}}
@if($view === 'open')
    @include('Shift.partials.open')
@endif

{{-- CLOSE SHIFT VIEW --}}
@if($view === 'close')
    @include('Shift.partials.close')
@endif

{{-- ARCHIVE VIEW --}}
@if($view === 'archive')
    @include('Shift.partials.archive')
@endif

@endsection

@push('scripts')
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
@endpush