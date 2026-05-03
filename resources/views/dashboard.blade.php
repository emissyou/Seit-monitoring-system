@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h4 class="mb-4">Employee</h4>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card p-3">
                <p class="text-muted mb-1" style="font-size: 13px;">Total Users</p>
                <h5 class="mb-0">1,284</h5>d
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <p class="text-muted mb-1" style="font-size: 13px;">Active</p>
                <h5 class="mb-0">938</h5>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <p class="text-muted mb-1" style="font-size: 13px;">Admins</p>
                <h5 class="mb-0">12</h5>
            </div>
        </div>
    </div>


@endsection