@extends('layouts.app')

@section('title', 'Totalizer Log')

@push('styles')
<style>
    .table th {
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748b;
        font-weight: 600;
    }
    .fuel-badge {
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 500;
        background: #fff0f2;
        color: #D2042D;
    }
    .date-main {
        font-weight: 600;
        color: #1e293b;
    }
    .date-sub {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    /* Fix pagination size */
    .pagination {
        margin-bottom: 0;
        font-size: 0.85rem;
    }
    .pagination .page-link {
        padding: 4px 10px;
    }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Totalizer Log</h4>
        <p class="text-muted mb-0">Closing totalizer readings recorded per shift</p>
    </div>
    <a href="{{ route('totalizer.export', request()->query()) }}"
       class="btn btn-outline-danger">
        <i class="bi bi-download me-1"></i> Export CSV
    </a>
</div>

{{-- Filters --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('totalizer.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Pump</label>
                <select name="pump_id" class="form-select">
                    <option value="">All Pumps</option>
                    @foreach($pumps as $pump)
                        <option value="{{ $pump->PumpID }}" {{ request('pump_id') == $pump->PumpID ? 'selected' : '' }}>
                            {{ $pump->pump_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Fuel</label>
                <select name="fuel_id" class="form-select">
                    <option value="">All Fuels</option>
                    @foreach($fuels as $fuel)
                        <option value="{{ $fuel->FuelID }}" {{ request('fuel_id') == $fuel->FuelID ? 'selected' : '' }}>
                            {{ $fuel->fuel_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Date From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Date To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-danger w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('totalizer.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Table --}}
<div class="card shadow-sm">
    <div class="card-body">

        {{-- Result count inside card, above table --}}
        @if($logs->total() > 0)
            <p class="text-muted small mb-3">
                Showing {{ $logs->firstItem() }}–{{ $logs->lastItem() }} of {{ $logs->total() }} results
            </p>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date Recorded</th>
                        <th>Pump</th>
                        <th>Fuel Type</th>
                        <th class="text-end">Closing Reading (L)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>
                                @if($log->date_recorded)
                                    <span class="date-main">
                                        {{ date('M d, Y', strtotime($log->date_recorded)) }}
                                    </span>
                                    @if(!empty($log->closed_at))
                                        <br>
                                        <span class="date-sub">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ date('h:i A', strtotime($log->closed_at)) }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $log->pump_name }}</td>
                            <td><span class="fuel-badge">{{ $log->fuel_name }}</span></td>
                            <td class="text-end fw-semibold">{{ number_format($log->reading, 3) }} L</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-speedometer2 fs-3 d-block mb-2"></i>
                                <strong>No totalizer records found.</strong>
                                <div class="small mt-1">Try adjusting your filters or close a shift to generate a log.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <span class="text-muted small">
                @if($logs->total() > 0)
                    Page {{ $logs->currentPage() }} of {{ $logs->lastPage() }}
                @endif
            </span>
            <div>
                {{ $logs->links('pagination::bootstrap-4') }}
            </div>
        </div>

    </div>
</div>

@endsection