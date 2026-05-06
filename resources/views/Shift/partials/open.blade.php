{{-- OPEN SHIFT --}}
<style>
    .open-shift-wrap * { box-sizing: border-box; font-family: 'Geist', 'DM Sans', system-ui, sans-serif; }

    .shift-subpage-header {
        margin-bottom: 24px;
    }
    .shift-subpage-header .breadcrumb-label {
        font-size: 11px; font-weight: 600; letter-spacing: .08em;
        text-transform: uppercase; color: #9ca3af; margin-bottom: 4px;
    }
    .shift-subpage-header h1 { font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 2px; }
    .shift-subpage-header p { font-size: 13px; color: #6b7280; margin: 0; }

    .alert-card {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 16px 20px; border-radius: 12px; font-size: 13px;
        margin-bottom: 20px;
    }
    .alert-card.warning { background: #fef3c7; border: 1px solid #fde68a; color: #92400e; }
    .alert-card.warning i { color: #d97706; font-size: 16px; margin-top: 1px; }

    .open-shift-card {
        background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; overflow: hidden;
    }
    .open-shift-card-header {
        padding: 20px 24px 16px;
        border-bottom: 1px solid #f3f4f6;
        display: flex; align-items: center; gap: 12px;
    }
    .open-shift-card-header .header-icon {
        width: 40px; height: 40px; border-radius: 10px;
        background: #d1fae5; color: #10b981;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
    .open-shift-card-header h2 { font-size: 16px; font-weight: 700; color: #111827; margin: 0 0 2px; }
    .open-shift-card-header p { font-size: 12px; color: #9ca3af; margin: 0; }

    .open-shift-card-body { padding: 24px; }

    .pump-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
    @media (max-width: 640px) { .pump-grid { grid-template-columns: 1fr; } }

    .pump-card-new {
        border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden;
        transition: border-color .15s;
    }
    .pump-card-new:hover { border-color: #10b981; }

    .pump-card-head {
        padding: 12px 16px;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        display: flex; align-items: center; gap: 8px;
    }
    .pump-card-head .pump-icon {
        width: 28px; height: 28px; border-radius: 7px;
        background: #d1fae5; color: #10b981;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px;
    }
    .pump-card-head strong { font-size: 13px; font-weight: 700; color: #111827; }
    .pump-card-body { padding: 16px; }

    .fuel-input-group { margin-bottom: 14px; }
    .fuel-input-group:last-child { margin-bottom: 0; }
    .fuel-input-group label {
        display: flex; align-items: center; gap: 6px;
        font-size: 12px; font-weight: 600; color: #6b7280;
        margin-bottom: 6px;
    }
    .fuel-dot { width: 7px; height: 7px; border-radius: 50%; }
    .fuel-input-field {
        width: 100%; padding: 9px 12px; font-size: 13px;
        border: 1px solid #e5e7eb; border-radius: 8px;
        background: #f9fafb; color: #111827; outline: none;
        transition: border-color .15s, box-shadow .15s;
    }
    .fuel-input-field:focus {
        border-color: #10b981; background: #fff;
        box-shadow: 0 0 0 3px rgba(16,185,129,.1);
    }

    .submit-section { margin-top: 28px; display: flex; align-items: center; gap: 14px; }
    .btn-open-shift {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 28px; border-radius: 9px;
        background: #10b981; color: #fff;
        font-size: 14px; font-weight: 700; border: none;
        cursor: pointer; transition: background .15s;
    }
    .btn-open-shift:hover { background: #059669; }
    .btn-cancel-link {
        font-size: 13px; color: #9ca3af; text-decoration: none;
        transition: color .15s;
    }
    .btn-cancel-link:hover { color: #374151; }

    .errors-list {
        background: #fee2e2; border: 1px solid #fecaca;
        border-radius: 10px; padding: 14px 18px; margin-bottom: 20px;
        font-size: 13px; color: #991b1b;
    }
    .errors-list ul { margin: 0; padding-left: 18px; }
</style>

<div class="open-shift-wrap">
    <div class="shift-subpage-header">
        <div class="breadcrumb-label">Operations • Shift</div>
        <h1>Open New Shift</h1>
        <p>Enter the opening totalizer readings for all pumps.</p>
    </div>

    @if($activeShift)
        <div class="alert-card warning">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Shift already open.</strong> You must close the current shift before opening a new one.
                <br>
                <button onclick="cancelOpenShift({{ $activeShift->ShiftID }})" style="margin-top:10px; padding: 6px 14px; font-size:12px; font-weight:600; background:transparent; border:1px solid #d97706; color:#92400e; border-radius:6px; cursor:pointer;">
                    Cancel Open Shift
                </button>
            </div>
        </div>

    @else
        @if($errors->any())
            <div class="errors-list">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(($pumps ?? collect())->isEmpty())
            <div class="alert-card warning">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>No pumps configured. Please add pumps and fuel types in settings before opening a shift.</div>
            </div>
        @else
            <div class="open-shift-card">
                <div class="open-shift-card-header">
                    <div class="header-icon"><i class="bi bi-play-circle-fill"></i></div>
                    <div>
                        <h2>Opening Readings</h2>
                        <p>Enter totalizer value at the start of this shift for each pump nozzle.</p>
                    </div>
                </div>

                <div class="open-shift-card-body">
                    <form method="POST" action="{{ route('shift.open') }}">
                        @csrf

                        @php
                            $fuelDots = [
                                'Premium' => '#f59e0b',
                                'Regular' => '#10b981',
                                'Diesel'  => '#ef4444',
                            ];
                        @endphp

                        <div class="pump-grid">
                            @foreach($pumps ?? [] as $pump)
                                <div class="pump-card-new">
                                    <div class="pump-card-head">
                                        <div class="pump-icon"><i class="bi bi-fuel-pump"></i></div>
                                        <strong>{{ $pump->pump_name }}</strong>
                                    </div>
                                    <div class="pump-card-body">
                                        @foreach($pump->pumpFuels ?? [] as $pumpFuel)
                                            @php
                                                $fuelName = $pumpFuel->fuel->fuel_name ?? 'Fuel';
                                                $dotColor = $fuelDots[$fuelName] ?? '#6366f1';
                                            @endphp
                                            <div class="fuel-input-group">
                                                <label>
                                                    <span class="fuel-dot" style="background:{{ $dotColor }};"></span>
                                                    {{ $fuelName }} — Opening Reading (L)
                                                </label>
                                                <input type="number"
                                                       step="0.001"
                                                       name="opening_readings[{{ $pumpFuel->PumpFuelID }}]"
                                                       class="fuel-input-field"
                                                       value="{{ old('opening_readings.'.$pumpFuel->PumpFuelID) }}"
                                                       placeholder="{{ number_format($pumpFuel->totalizer_reading, 3) }}"
                                                       required>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="submit-section">
                            <button type="submit" class="btn-open-shift">
                                <i class="bi bi-play-circle"></i> Open Shift
                            </button>
                            <a href="{{ route('shift.management', ['view' => 'home']) }}" class="btn-cancel-link">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endif
</div>