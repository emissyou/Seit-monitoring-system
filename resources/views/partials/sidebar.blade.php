<!-- resources/views/partials/sidebar.blade.php -->

<div id="sidebar" class="border-end">
    
    <!-- Logo / Header - Removed underline -->
    <div class="p-4">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-fuel-pump fs-3 text-danger"></i>
            <div>
                <h5 class="mb-0 fw-bold text-danger">Seal Gas</h5>
                <small class="text-muted">Station Management</small>
            </div>
        </div>
    </div>

    <div class="p-3">
        <ul class="nav flex-column gap-1">

            <!-- Shift Management -->
            <li>
                <a href="{{ route('shift.management') }}" 
                   class="sidebar-link {{ request()->routeIs('shift.management*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history sidebar-icon"></i>
                    <span class="nav-label">Shift Management</span>
                </a>
            </li>

            <!-- Customers -->
            <li>
                <a href="{{ route('customers') }}" 
                   class="sidebar-link {{ request()->routeIs('customers*') ? 'active' : '' }}">
                    <i class="bi bi-people sidebar-icon"></i>
                    <span class="nav-label">Customers</span>
                </a>
            </li>

            <!-- Credit Logs -->
            <li>
                <a href="{{ route('credits.index') }}" 
                   class="sidebar-link {{ request()->routeIs('credits.index') ? 'active' : '' }}">
                    <i class="bi bi-credit-card sidebar-icon"></i>
                    <span class="nav-label">Credit Logs</span>
                </a>
            </li>

            <!-- Discount Logs -->
            <li>
                <a href="{{ route('discounts.index') }}" 
                   class="sidebar-link {{ request()->routeIs('discounts.index') ? 'active' : '' }}">
                    <i class="bi bi-tags sidebar-icon"></i>
                    <span class="nav-label">Discount Logs</span>
                </a>
            </li>

            <!-- Management Section -->
            <li class="mt-4">
                <div class="text-uppercase small fw-bold text-muted px-3 mb-2 nav-label">Management</div>
            </li>

            <li>
                <a href="{{ route('fuels.index') }}" 
                   class="sidebar-link {{ request()->routeIs('fuels*') ? 'active' : '' }}">
                    <i class="bi bi-droplet sidebar-icon"></i>
                    <span class="nav-label">Fuels</span>
                </a>
            </li>

            <li>
                <a href="{{ route('pumps.index') }}" 
                   class="sidebar-link {{ request()->routeIs('pumps*') ? 'active' : '' }}">
                    <i class="bi bi-fuel-pump sidebar-icon"></i>
                    <span class="nav-label">Pumps</span>
                </a>
            </li>

        </ul>
    </div>
</div>