@extends('layouts.app')

@section('title', 'Fuels')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Fuel Types</h4>
        <p class="text-muted small mb-0">Manage available fuel types</p>
    </div>
    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addFuelModal">
        <i class="bi bi-plus-lg me-1"></i> Add Fuel
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Fuels Table --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        @if($fuels->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-droplet" style="font-size:48px; opacity:.3;"></i>
                <p class="mt-3">No fuel types yet. Click <strong>Add Fuel</strong> to get started.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Fuel Name</th>
                            <th>Pumps Assigned</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fuels as $fuel)
                        @php
                            $colors = [
                                'Premium' => ['bg' => '#fff3cd', 'text' => '#856404'],
                                'Diesel'  => ['bg' => '#d1e7dd', 'text' => '#0a3622'],
                                'Regular' => ['bg' => '#cfe2ff', 'text' => '#084298'],
                            ];
                            $color = $colors[$fuel->fuel_name] ?? ['bg' => '#e9ecef', 'text' => '#495057'];
                        @endphp
                        <tr>
                            <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge rounded-pill px-3 py-2"
                                      style="background:{{ $color['bg'] }};color:{{ $color['text'] }};font-size:13px;font-weight:600;">
                                    <i class="bi bi-droplet-fill me-1"></i>{{ $fuel->fuel_name }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary rounded-pill">
                                    {{ $fuel->pumpFuels->count() }} pump(s)
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border-0 rounded-3" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                        <li>
                                            {{-- Fuel primaryKey is 'FuelID', not 'id' --}}
                                            <a class="dropdown-item" href="#"
                                               onclick="openEditModal(
                                                   {{ $fuel->FuelID }},
                                                   '{{ addslashes($fuel->fuel_name) }}'
                                               )">
                                                <i class="bi bi-pencil me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#"
                                               onclick="deleteFuel({{ $fuel->FuelID }}, '{{ addslashes($fuel->fuel_name) }}')">
                                                <i class="bi bi-trash me-2"></i> Delete
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
        <div class="modal-content border-0 rounded-4 shadow">
            <form method="POST" action="{{ route('fuels.store') }}">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Add Fuel Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">
                    {{-- Fuel model only has 'fuel_name' in fillable --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Fuel Name <span class="text-danger">*</span></label>
                        <input type="text" name="fuel_name" class="form-control rounded-3"
                               placeholder="e.g. Premium, Diesel, Regular" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-3 px-4">Add Fuel</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Edit Fuel Modal ── --}}
<div class="modal fade" id="editFuelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form method="POST" id="editFuelForm">
                @csrf
                @method('PUT')
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Fuel Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Fuel Name <span class="text-danger">*</span></label>
                        <input type="text" name="fuel_name" id="edit_fuel_name"
                               class="form-control rounded-3" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-3 px-4">Save Changes</button>
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
    // Only fuel_name is passed — octane and description are not on the Fuel model
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