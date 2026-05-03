{{-- OPEN SHIFT --}}
@if($activeShift)
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        A shift is already open. Please close it first.
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h5>Current Open Shift Readings</h5>
            {{-- You can enhance this later with dynamic PumpFuel display --}}
            <button onclick="cancelOpenShift({{ $activeShift->id }})" class="btn btn-outline-danger mt-3">
                Cancel Open Shift
            </button>
        </div>
    </div>
@else
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-4">Open New Shift - Enter Opening Readings</h4>
            
            <form method="POST" action="{{ route('shift.open') }}">
                @csrf

                <div class="row g-4">
                    @foreach($pumps ?? [] as $pump)
                        <div class="col-md-6">
                            <div class="card pump-card">
                                <div class="card-header bg-light">
                                    <strong>{{ $pump->pump_name }}</strong>
                                </div>
                                <div class="card-body">
                                    @foreach($pump->pumpFuels ?? [] as $pumpFuel)
                                        <div class="mb-3">
                                            <label class="form-label">
                                                {{ $pumpFuel->fuel->fuel_name ?? 'Fuel' }} (Liters)
                                            </label>
                                            <input type="number" 
                                                   step="0.001" 
                                                   name="opening_readings[{{ $pumpFuel->id }}]" 
                                                   class="form-control"
                                                   value="{{ old('opening_readings.'.$pumpFuel->id) }}"
                                                   required>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-success btn-lg mt-4 px-5">
                    <i class="bi bi-play-circle me-2"></i>Open Shift
                </button>
            </form>
        </div>
    </div>
@endif