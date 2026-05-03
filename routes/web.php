<?php

use App\Http\Controllers\CreditController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\FuelController;
use App\Http\Controllers\PumpController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ── Customers ──────────────────────────────────────────────────────────────
Route::get('/customers',                [CustomerController::class, 'index'])->name('customers');
Route::post('/customers',               [CustomerController::class, 'store'])->name('customers.store');
Route::put('/customers/{id}',           [CustomerController::class, 'update'])->name('customers.update');
Route::patch('/customers/{id}/archive', [CustomerController::class, 'archive'])->name('customers.archive');
Route::delete('/customers/{id}',        [CustomerController::class, 'destroy'])->name('customers.destroy');

// ── Customer credits (JSON for modal) ─────────────────────────────────────
Route::get('/customers/{id}/credits',   [CreditController::class, 'byCustomer'])->name('credits.byCustomer');

// ── Credits ────────────────────────────────────────────────────────────────
Route::get('/credits',                  [CreditController::class, 'index'])->name('credits.index');
Route::post('/credits',                 [CreditController::class, 'store'])->name('credits.store');
Route::get('/credits/{id}/detail',      [CreditController::class, 'detail'])->name('credits.detail');
Route::post('/credits/{id}/pay',        [CreditController::class, 'pay'])->name('credits.pay');
Route::patch('/credits/{id}/status',    [CreditController::class, 'updateStatus'])->name('credits.status');
Route::patch('/credits/{id}/archive',   [CreditController::class, 'archive'])->name('credits.archive');
Route::delete('/credits/{id}',          [CreditController::class, 'destroy'])->name('credits.destroy');

// ── Discounts ──────────────────────────────────────────────────────────────
Route::get('/discounts',                [DiscountController::class, 'index'])->name('discounts.index');
Route::post('/discounts',               [DiscountController::class, 'store'])->name('discounts.store');
Route::patch('/discounts/{id}/archive', [DiscountController::class, 'archive'])->name('discounts.archive');
Route::delete('/discounts/{id}',        [DiscountController::class, 'destroy'])->name('discounts.destroy');

// ── Fuels ──────────────────────────────────────────────────────────────────
Route::get('/fuels',                    [FuelController::class, 'index'])->name('fuels.index');
Route::post('/fuels',                   [FuelController::class, 'store'])->name('fuels.store');
Route::put('/fuels/{id}',               [FuelController::class, 'update'])->name('fuels.update');
Route::delete('/fuels/{id}',            [FuelController::class, 'destroy'])->name('fuels.destroy');

// ── Pumps ──────────────────────────────────────────────────────────────────
Route::get('/pumps',                    [PumpController::class, 'index'])->name('pumps.index');
Route::post('/pumps',                   [PumpController::class, 'store'])->name('pumps.store');
Route::put('/pumps/{id}',               [PumpController::class, 'update'])->name('pumps.update');
Route::delete('/pumps/{id}',            [PumpController::class, 'destroy'])->name('pumps.destroy');

// ── Shifts ─────────────────────────────────────────────────────────────────
Route::get('/',                    [ShiftController::class, 'index'])->name('shift.management');
Route::post('/shift/open',              [ShiftController::class, 'open'])->name('shift.open');
Route::post('/shift/close',             [ShiftController::class, 'close'])->name('shift.close');
Route::get('/shift/{shift}',            [ShiftController::class, 'show'])->name('shift.view');
Route::get('/shift/{shift}/edit',       [ShiftController::class, 'edit'])->name('shift.edit');
Route::patch('/shift/{id}/archive',     [ShiftController::class, 'archive'])->name('shift.archive');
Route::patch('/shift/{id}/restore',     [ShiftController::class, 'restore'])->name('shift.restore');
Route::delete('/shift/{id}',            [ShiftController::class, 'destroy'])->name('shift.destroy');