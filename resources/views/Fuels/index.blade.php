@extends('layouts.app')

@section('title', 'Fuels')

@section('content')

{{-- ── Page Header ── --}}
<div class="d-flex align-items-start justify-content-between mb-4">
    <div style="border-left: 3px solid #E53935; padding-left: 14px;">
        <p class="text-uppercase mb-1" style="font-size:11px; font-weight:600; letter-spacing:.08em; color:#E53935;">
            Management &bull; Fuels
        </p>
        <h4 class="fw-bold mb-0" style="font-size:1.5rem; color:#111;">Fuel Types</h4>
        <p class="mb-0" style="font-size:13px; color:#888;">Manage available fuel types for your station</p>
    </div>
    <button class="btn btn-danger d-flex align-items-center gap-2 px-3 py-2 rounded-3 shadow-sm"
            style="background:#E53935; border-color:#E53935; font-size:14px; font-weight:500;"
            data-bs-toggle="modal" data-bs-target="#addFuelModal">
        <i class="bi bi-plus-lg"></i> Add Fuel
    </button>
</div>

{{-- ── Alerts ── --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert"
         style="background:#d1fae5; color:#065f46; font-size:14px;">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert"
         style="background:#fee2e2; color:#991b1b; font-size:14px;">
        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── Stat Cards ── --}}
<div class="row g-3 mb-4">
    {{-- Total Fuel Types --}}
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden position-relative" style="background:#fff;">
            <div class="position-absolute top-0 end-0 mt-2 me-2"
                 style="width:80px;height:80px;background:rgba(229,57,53,.12);border-radius:50%;"></div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-3"
                         style="width:38px;height:38px;background:rgba(229,57,53,.12);">
                        <i class="bi bi-droplet-fill" style="color:#E53935;font-size:16px;"></i>
                    </div>
                </div>
                <p class="text-uppercase mb-1" style="font-size:10px;font-weight:700;letter-spacing:.08em;color:#999;">
                    Total Fuel Types
                </p>
                <h3 class="fw-bold mb-0" style="font-size:1.6rem;color:#111;">{{ $fuels->count() }}</h3>
                <p class="mb-0 mt-1" style="font-size:12px;color:#aaa;">configured types</p>
            </div>
        </div>
    </div>

    {{-- Total Pumps Assigned --}}
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden position-relative" style="background:#fff;">
            <div class="position-absolute top-0 end-0 mt-2 me-2"
                 style="width:80px;height:80px;background:rgba(16,185,129,.12);border-radius:50%;"></div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-3"
                         style="width:38px;height:38px;background:rgba(16,185,129,.12);">
                        <i class="bi bi-fuel-pump-fill" style="color:#10b981;font-size:16px;"></i>
                    </div>
                </div>
                <p class="text-uppercase mb-1" style="font-size:10px;font-weight:700;letter-spacing:.08em;color:#999;">
                    Pumps Assigned
                </p>
                <h3 class="fw-bold mb-0" style="font-size:1.6rem;color:#111;">
                    {{ $fuels->sum(fn($f) => $f->pumpFuels->count()) }}
                </h3>
                <p class="mb-0 mt-1" style="font-size:12px;color:#aaa;">across all fuels</p>
            </div>
        </div>
    </div>
</div>

{{-- ── Fuels Table ── --}}
<div class="card border-0 shadow-sm rounded-4" style="overflow:hidden;">
    <div class="card-body p-0">
        @if($fuels->isEmpty())
            <div class="text-center py-5" style="color:#bbb;">
                <i class="bi bi-droplet" style="font-size:52px; opacity:.25;"></i>
                <p class="mt-3 mb-0" style="font-size:14px;">No fuel types yet.</p>
                <p style="font-size:13px; color:#ccc;">Click <strong style="color:#E53935;">Add Fuel</strong> to get started.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background:#fafafa; border-bottom:1px solid #f0f0f0;">
                            <th class="ps-4 py-3"
                                style="font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#999;border:none;">
                                #
                            </th>
                            <th class="py-3"
                                style="font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#999;border:none;">
                                Fuel Name
                            </th>
                            <th class="py-3"
                                style="font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#999;border:none;">
                                Pumps Assigned
                            </th>
                            <th class="text-end pe-4 py-3"
                                style="font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#999;border:none;">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fuels as $fuel)
                        @php
                            $colors = [
                                'Premium' => ['bg' => '#fff8e1', 'text' => '#b45309', 'dot' => '#f59e0b'],
                                'Diesel'  => ['bg' => '#ecfdf5', 'text' => '#065f46', 'dot' => '#10b981'],
                                'Regular' => ['bg' => '#eff6ff', 'text' => '#1d4ed8', 'dot' => '#3b82f6'],
                            ];
                            $color = $colors[$fuel->fuel_name] ?? ['bg' => '#f3f4f6', 'text' => '#374151', 'dot' => '#9ca3af'];
                        @endphp
                        <tr style="border-bottom:1px solid #f7f7f7;">
                            <td class="ps-4 py-3" style="font-size:13px;color:#bbb;width:48px;">
                                {{ $loop->iteration }}
                            </td>
                            <td class="py-3">
                                <span class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill"
                                      style="background:{{ $color['bg'] }};color:{{ $color['text'] }};font-size:13px;font-weight:600;">
                                    <span style="width:7px;height:7px;border-radius:50%;background:{{ $color['dot'] }};display:inline-block;"></span>
                                    {{ $fuel->fuel_name }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="px-3 py-1 rounded-pill"
                                      style="background:#f3f4f6;color:#6b7280;font-size:12px;font-weight:500;">
                                    {{ $fuel->pumpFuels->count() }} pump(s)
                                </span>
                            </td>
                            <td class="text-end pe-4 py-3">
                                <div class="dropdown">
                                    <button class="btn btn-sm rounded-3 border-0"
                                            style="background:#f9f9f9;width:32px;height:32px;padding:0;display:inline-flex;align-items:center;justify-content:center;"
                                            data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical" style="color:#888;font-size:14px;"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 py-1"
                                        style="min-width:150px;font-size:13px;">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-3" href="#"
                                               style="color:#374151;"
                                               onclick="openEditModal(
                                                   {{ $fuel->FuelID }},
                                                   '{{ addslashes($fuel->fuel_name) }}'
                                               )">
                                                <i class="bi bi-pencil" style="color:#6b7280;"></i> Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider my-1" style="border-color:#f0f0f0;"></li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-3" href="#"
                                               style="color:#E53935;"
                                               onclick="deleteFuel({{ $fuel->FuelID }}, '{{ addslashes($fuel->fuel_name) }}')">
                                                <i class="bi bi-trash"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- ── Add Fuel Modal ── --}}
<div class="modal fade" id="addFuelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <form method="POST" action="{{ route('fuels.store') }}">
                @csrf
                <div class="modal-header border-0 px-4 pt-4 pb-2">
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="font-size:1.1rem;color:#111;">Add Fuel Type</h5>
                        <p class="mb-0 mt-1" style="font-size:12px;color:#aaa;">Enter the new fuel type name below.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <label class="form-label fw-semibold" style="font-size:13px;color:#374151;">
                        Fuel Name <span style="color:#E53935;">*</span>
                    </label>
                    <input type="text" name="fuel_name"
                           class="form-control rounded-3 border-0"
                           style="background:#f9f9f9;font-size:14px;padding:.65rem 1rem;box-shadow:none;"
                           placeholder="e.g. Premium, Diesel, Regular" required>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                    <button type="button" class="btn rounded-3 px-4"
                            style="background:#f3f4f6;color:#374151;font-size:13px;font-weight:500;border:none;"
                            data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn rounded-3 px-4"
                            style="background:#E53935;color:#fff;font-size:13px;font-weight:500;border:none;">
                        Add Fuel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Edit Fuel Modal ── --}}
<div class="modal fade" id="editFuelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <form method="POST" id="editFuelForm">
                @csrf
                @method('PUT')
                <div class="modal-header border-0 px-4 pt-4 pb-2">
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="font-size:1.1rem;color:#111;">Edit Fuel Type</h5>
                        <p class="mb-0 mt-1" style="font-size:12px;color:#aaa;">Update the fuel type name below.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <label class="form-label fw-semibold" style="font-size:13px;color:#374151;">
                        Fuel Name <span style="color:#E53935;">*</span>
                    </label>
                    <input type="text" name="fuel_name" id="edit_fuel_name"
                           class="form-control rounded-3 border-0"
                           style="background:#f9f9f9;font-size:14px;padding:.65rem 1rem;box-shadow:none;" required>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                    <button type="button" class="btn rounded-3 px-4"
                            style="background:#f3f4f6;color:#374151;font-size:13px;font-weight:500;border:none;"
                            data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn rounded-3 px-4"
                            style="background:#E53935;color:#fff;font-size:13px;font-weight:500;border:none;">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Delete form (hidden) ── --}}
<form id="deleteFuelForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
    function openEditModal(id, name) {
        document.getElementById('edit_fuel_name').value = name;
        document.getElementById('editFuelForm').action  = `/fuels/${id}`;
        new bootstrap.Modal(document.getElementById('editFuelModal')).show();
    }

    function deleteFuel(id, name) {
        if (!confirm(`Delete fuel "${name}"? This may affect pumps and shifts using this fuel.`)) return;
        const form = document.getElementById('deleteFuelForm');
        form.action = `/fuels/${id}`;
        form.submit();
    }
</script>
@endpush