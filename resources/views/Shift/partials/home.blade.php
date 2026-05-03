{{-- HOME / DASHBOARD --}}
<div class="row g-4">

    {{-- Current Shift Status --}}
    <div class="col-12">
        <div class="card shadow-sm border-{{ $activeShift ? 'success' : 'secondary' }}">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="status-dot rounded-circle {{ $activeShift ? 'bg-success' : 'bg-secondary' }}" 
                         style="width: 14px; height: 14px;"></div>
                    <div>
                        <h5 class="mb-1 fw-bold">
                            @if($activeShift)
                                Shift is <span class="text-success">OPEN</span>
                            @else
                                No Active Shift
                            @endif
                        </h5>
                        <small class="text-muted">
                            @if($activeShift)
                                Opened: {{ $activeShift->opened_at?->format('M d, Y • h:i A') }}
                            @elseif($latestClosedShift)
                                Last closed: {{ $latestClosedShift->closed_at?->format('M d, Y • h:i A') }}
                            @else
                                No shifts yet
                            @endif
                        </small>
                    </div>
                </div>

                <div>
                    @if($activeShift)
                        <a href="{{ route('shift.management', ['view' => 'close']) }}" class="btn btn-danger btn-lg">
                            <i class="bi bi-stop-circle me-2"></i>Close Shift
                        </a>
                    @else
                        <a href="{{ route('shift.management', ['view' => 'open']) }}" class="btn btn-success btn-lg">
                            <i class="bi bi-play-circle me-2"></i>Open Shift
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase">Total Liters</div>
                <div class="stat-value text-primary">{{ number_format($totals['liters'] ?? 0, 3) }} <small>L</small></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase">Gross Sales</div>
                <div class="stat-value">₱{{ number_format($totals['gross'] ?? 0, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase">Net Sales</div>
                <div class="stat-value text-success">₱{{ number_format($totals['net'] ?? 0, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase">Cash in Hand</div>
                <div class="stat-value">₱{{ number_format($totals['cash_in_hand'] ?? 0, 2) }}</div>
            </div>
        </div>
    </div>

    {{-- Fuel Performance --}}
    <div class="col-12">
        <h5 class="section-title mb-3">Fuel Performance</h5>
        <div class="row g-3">
            @php
                $fuelColors = [
                    'Premium' => ['bg' => '#fef3c7', 'text' => '#92400e', 'bar' => '#f59e0b'],
                    'Regular' => ['bg' => '#d1fae5', 'text' => '#065f46', 'bar' => '#10b981'],
                    'Diesel'  => ['bg' => '#fee2e2', 'text' => '#991b1b', 'bar' => '#ef4444'],
                ];
            @endphp
            @foreach($fuelTotals as $fuelName => $fuel)
                @php $c = $fuelColors[$fuelName] ?? ['bg'=>'#e0e7ff','text'=>'#4338ca','bar'=>'#6366f1']; @endphp
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="fuel-badge px-3 py-1" style="background:{{ $c['bg'] }}; color:{{ $c['text'] }};">
                                    {{ $fuelName }}
                                </span>
                                <span class="text-muted small">{{ number_format($fuel['liters'] ?? 0, 3) }} L</span>
                            </div>
                            <div class="mt-3 fw-bold fs-4">₱{{ number_format($fuel['value'] ?? 0, 2) }}</div>
                            <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar" style="background:{{ $c['bar'] }}; width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Filter + History Table --}}
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Shift History</h5>
                
                {{-- Filter Form --}}
                <form method="GET" class="row g-3 mb-4">
                    <input type="hidden" name="view" value="home">
                    <div class="col-md-2">
                        <label class="form-label small">From</label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">To</label>
                        <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="all" @selected($statusFilter === 'all')>All</option>
                            <option value="open" @selected($statusFilter === 'open')>Open</option>
                            <option value="closed" @selected($statusFilter === 'closed')>Closed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Archived</label>
                        <select name="archived" class="form-select form-select-sm">
                            <option value="false" @selected($archivedFilter === 'false')>Active</option>
                            <option value="true" @selected($archivedFilter === 'true')>Archived</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                    </div>
                </form>

                {{-- History Table --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Liters</th>
                                <th>Gross</th>
                                <th>Discount</th>
                                <th>Credit</th>
                                <th>Net</th>
                                <th>Cash</th>
                                <th>Closed At</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shifts as $shift)
                                <tr>
                                    <td>{{ $shift->sales_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $shift->status === 'open' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($shift->status) }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($shift->totalizer_liters ?? 0, 3) }} L</td>
                                    <td>₱{{ number_format($shift->computed_gross_sales ?? 0, 2) }}</td>
                                    <td>₱{{ number_format($shift->total_discount ?? 0, 2) }}</td>
                                    <td>₱{{ number_format($shift->total_credit ?? 0, 2) }}</td>
                                    <td class="fw-semibold">₱{{ number_format($shift->computed_net_sales ?? 0, 2) }}</td>
                                    <td>₱{{ number_format($shift->computed_cash_in_hand ?? 0, 2) }}</td>
                                    <td class="text-muted small">{{ $shift->closed_at?->format('M d, h:i A') ?? '—' }}</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @if($shift->archived)
                                                    <li><a class="dropdown-item" href="#" onclick="restoreShift({{ $shift->id }})">Restore</a></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteShift({{ $shift->id }})">Delete</a></li>
                                                @else
                                                    <li><a class="dropdown-item" href="{{ route('shift.view', $shift) }}">View</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('shift.edit', $shift) }}">Edit</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-warning" href="#" onclick="archiveShift({{ $shift->id }})">Archive</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="10" class="text-center py-5 text-muted">No shifts found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>