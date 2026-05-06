{{-- ARCHIVE VIEW --}}
<style>
    .archive-wrap * { box-sizing: border-box; font-family: 'Geist', 'DM Sans', system-ui, sans-serif; }

    .archive-wrap .shift-subpage-header { margin-bottom: 24px; }
    .archive-wrap .breadcrumb-label {
        font-size: 11px; font-weight: 600; letter-spacing: .08em;
        text-transform: uppercase; color: #9ca3af; margin-bottom: 4px;
    }
    .archive-wrap h1 { font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 2px; }
    .archive-wrap .page-sub { font-size: 13px; color: #6b7280; margin: 0; }

    .archive-card {
        background: #fff; border: 1px solid #e5e7eb;
        border-radius: 14px; overflow: hidden;
    }
    .archive-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #f3f4f6;
        display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
    }
    .archive-card-header-left { display: flex; align-items: center; gap: 12px; }
    .archive-header-icon {
        width: 38px; height: 38px; border-radius: 10px;
        background: #fef3c7; color: #d97706;
        display: flex; align-items: center; justify-content: center;
        font-size: 17px;
    }
    .archive-title { font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 2px; }
    .archive-desc { font-size: 12px; color: #9ca3af; }

    .archive-count-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 700;
        background: #f3f4f6; color: #374151;
    }

    /* Filter */
    .filter-row {
        display: flex; gap: 10px; align-items: flex-end; flex-wrap: wrap;
        padding: 16px 22px; border-bottom: 1px solid #f3f4f6;
    }
    .filter-field label {
        display: block; font-size: 11px; font-weight: 600; color: #6b7280;
        margin-bottom: 4px; text-transform: uppercase; letter-spacing: .04em;
    }
    .filter-field input {
        border: 1px solid #e5e7eb; border-radius: 8px; padding: 7px 12px;
        font-size: 13px; color: #111827; background: #f9fafb; outline: none; height: 36px;
    }
    .filter-field input:focus { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.1); }

    .btn-filter {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 0 18px; height: 36px; border-radius: 8px;
        background: #ef4444; color: #fff; font-size: 13px; font-weight: 600;
        border: none; cursor: pointer; transition: background .15s;
    }
    .btn-filter:hover { background: #dc2626; }

    .btn-clear {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 0 16px; height: 36px; border-radius: 8px;
        background: transparent; color: #6b7280; font-size: 13px; font-weight: 500;
        border: 1px solid #e5e7eb; text-decoration: none; transition: all .15s;
    }
    .btn-clear:hover { border-color: #9ca3af; color: #374151; }

    /* Table */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead tr { border-bottom: 1px solid #f3f4f6; }
    .data-table thead th {
        padding: 10px 14px; font-size: 10px; font-weight: 700;
        letter-spacing: .07em; text-transform: uppercase; color: #9ca3af;
        text-align: left; white-space: nowrap;
    }
    .data-table tbody tr {
        border-bottom: 1px solid #f9fafb; transition: background .1s;
    }
    .data-table tbody tr:last-child { border-bottom: none; }
    .data-table tbody tr:hover { background: #fffbeb; }
    .data-table tbody td { padding: 12px 14px; font-size: 13px; color: #374151; }

    .archived-tag {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;
        background: #fef3c7; color: #92400e;
    }

    /* Action buttons */
    .action-group { display: flex; align-items: center; gap: 6px; justify-content: flex-end; }
    .btn-icon {
        display: inline-flex; align-items: center; justify-content: center;
        height: 30px; border-radius: 7px; font-size: 12px; font-weight: 600;
        border: 1px solid; cursor: pointer; transition: all .15s; gap: 5px; padding: 0 10px;
        text-decoration: none;
    }
    .btn-icon.view   { border-color: #dbeafe; background: #eff6ff; color: #2563eb; }
    .btn-icon.view:hover   { background: #dbeafe; }
    .btn-icon.restore { border-color: #d1fae5; background: #f0fdf4; color: #059669; }
    .btn-icon.restore:hover { background: #d1fae5; }
    .btn-icon.delete  { border-color: #fecaca; background: #fff5f5; color: #dc2626; }
    .btn-icon.delete:hover  { background: #fee2e2; }

    /* Empty state */
    .empty-state {
        padding: 60px 22px; text-align: center; color: #9ca3af;
    }
    .empty-state .empty-icon {
        width: 56px; height: 56px; border-radius: 14px;
        background: #f3f4f6; display: flex; align-items: center; justify-content: center;
        font-size: 24px; color: #d1d5db; margin: 0 auto 14px;
    }
    .empty-state h3 { font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 4px; }
    .empty-state p { font-size: 13px; margin: 0; }

    .pagination-wrap { padding: 14px 22px; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end; }
</style>

<div class="archive-wrap">
    <div class="shift-subpage-header">
        <div class="breadcrumb-label">Operations • Shift</div>
        <h1>Archived Shifts</h1>
        <p class="page-sub">Hidden from the main dashboard. Restore or permanently delete records here.</p>
    </div>

    <div class="archive-card">
        {{-- Header --}}
        <div class="archive-card-header">
            <div class="archive-card-header-left">
                <div class="archive-header-icon"><i class="bi bi-archive-fill"></i></div>
                <div>
                    <div class="archive-title">Archive</div>
                    <div class="archive-desc">Manage soft-deleted shift records</div>
                </div>
            </div>
            <div class="archive-count-badge">
                <i class="bi bi-stack"></i>
                {{ $archivedShifts->total() }} total
            </div>
        </div>

        {{-- Filter --}}
        <form method="GET">
            <input type="hidden" name="view" value="archive">
            <div class="filter-row">
                <div class="filter-field">
                    <label>From</label>
                    <input type="date" name="archive_from" value="{{ request('archive_from') }}">
                </div>
                <div class="filter-field">
                    <label>To</label>
                    <input type="date" name="archive_to" value="{{ request('archive_to') }}">
                </div>
                <button type="submit" class="btn-filter">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                @if(request('archive_from') || request('archive_to'))
                    <a href="{{ route('shift.management', ['view' => 'archive']) }}" class="btn-clear">
                        <i class="bi bi-x"></i> Clear
                    </a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Liters</th>
                        <th>Gross</th>
                        <th>Discount</th>
                        <th>Credit</th>
                        <th>Net</th>
                        <th>Cash</th>
                        <th>Closed At</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($archivedShifts as $shift)
                        <tr>
                            <td>
                                <div style="font-weight:600; color:#111827;">{{ $shift->sales_date->format('M d, Y') }}</div>
                                <div style="margin-top:3px;">
                                    <span class="archived-tag"><i class="bi bi-archive" style="font-size:9px;"></i> Archived</span>
                                </div>
                            </td>
                            <td>{{ number_format($shift->db_liters, 3) }} L</td>
                            <td>₱{{ number_format($shift->db_gross, 2) }}</td>
                            <td style="color:#ef4444;">₱{{ number_format($shift->db_discount, 2) }}</td>
                            <td>₱{{ number_format($shift->db_credit, 2) }}</td>
                            <td style="font-weight:700; color:#111827;">₱{{ number_format($shift->db_net, 2) }}</td>
                            <td>₱{{ number_format($shift->db_cash_in_hand, 2) }}</td>
                            <td style="color:#9ca3af; font-size:12px;">{{ $shift->closed_at?->format('M d, h:i A') ?? '—' }}</td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('shift.view', $shift->ShiftID) }}" class="btn-icon view" title="View shift details">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <button type="button" class="btn-icon restore" onclick="restoreShift({{ $shift->ShiftID }})" title="Restore to dashboard">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </button>
                                    <button type="button" class="btn-icon delete" onclick="deleteShift({{ $shift->ShiftID }})" title="Permanently delete">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="bi bi-archive"></i></div>
                                    <h3>No archived shifts</h3>
                                    <p>Archived shifts will appear here. Use the Archive option from the shift actions menu.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="pagination-wrap">
            {{ $archivedShifts->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>