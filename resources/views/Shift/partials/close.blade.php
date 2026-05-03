@if(!$activeShift)
    <div class="alert alert-warning">No active shift to close.</div>
@else
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-4">Close Shift — {{ $activeShift->sales_date->format('M d, Y') }}</h4>

            <form method="POST" action="{{ route('shift.close') }}" id="close-shift-form">
                @csrf
                <input type="hidden" name="shift_id" value="{{ $activeShift->id }}">

                <!-- Opening Readings Summary -->
                <div class="mb-5">
                    <h6 class="fw-semibold mb-3">Opening Readings</h6>
                    <div class="row g-3">
                        @foreach($activeShift->opening_readings ?? [] as $pfId => $reading)
                            <div class="col-md-4">
                                <div class="small text-muted">PumpFuel #{{ $pfId }}</div>
                                <strong>{{ number_format($reading, 3) }} L</strong>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Closing Readings -->
                <div class="mb-5">
                    <h6 class="fw-semibold mb-3">Closing Totalizer Readings</h6>
                    <div class="row g-4">
                        @foreach($pumps as $pump)
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-light fw-semibold">{{ $pump->pump_name }}</div>
                                    <div class="card-body">
                                        @foreach($pump->pumpFuels as $pf)
                                            <div class="mb-3">
                                                <label class="form-label">{{ $pf->fuel->fuel_name ?? 'Fuel' }}</label>
                                                <input type="number" 
                                                       step="0.001" 
                                                       name="closing_readings[{{ $pf->id }}]" 
                                                       class="form-control closing-reading"
                                                       data-pf-id="{{ $pf->id }}"
                                                       value="{{ old('closing_readings.'.$pf->id) }}"
                                                       required>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Fuel Prices -->
                <div class="mb-5">
                    <h6 class="fw-semibold mb-3">Fuel Prices Today</h6>
                    <div class="row g-3">
                        @foreach($fuels as $fuel)
                            <div class="col-md-4">
                                <label class="form-label">{{ $fuel->fuel_name }} (₱/L)</label>
                                <input type="number" 
                                       step="0.01" 
                                       name="prices[{{ $fuel->id }}]" 
                                       class="form-control price-input"
                                       value="{{ old('prices.'.$fuel->id) }}" 
                                       required>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Live Calculation -->
                <div class="mb-5">
                    <h6 class="fw-semibold mb-3">Live Gross Sales Calculation</h6>
                    <div id="live-calculation" class="row g-3"></div>
                    <div class="mt-3">
                        <strong>Gross Sales: ₱<span id="gross-total" class="fs-4 text-primary">0.00</span></strong>
                    </div>
                </div>

                <!-- Discounts & Credits (same as before, but cleaner) -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h6>Discounts</h6>
                        <table class="table table-sm" id="discount-table">
                            <thead class="table-light"><tr><th>Fuel</th><th>Customer</th><th>Liters</th><th>Disc/L</th><th>Total</th><th></th></tr></thead>
                            <tbody id="discounts-body"></tbody>
                        </table>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addDiscountRow()">+ Add Discount</button>
                    </div>
                    <div class="col-lg-6">
                        <h6>Credits</h6>
                        <table class="table table-sm" id="credit-table">
                            <thead class="table-light"><tr><th>Customer</th><th>Liters</th><th>Price</th><th>Amount</th><th>Disc?</th><th></th></tr></thead>
                            <tbody id="credits-body"></tbody>
                        </table>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addCreditRow()">+ Add Credit</button>
                    </div>
                </div>

                <hr class="my-5">

                <button type="submit" class="btn btn-danger btn-lg px-5">
                    <i class="bi bi-stop-circle me-2"></i> Close This Shift
                </button>
            </form>
        </div>
    </div>
@endif