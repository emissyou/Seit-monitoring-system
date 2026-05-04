<!-- resources/views/partials/sidebar.blade.php -->

<div id="sidebar" class="border-end">

    <!-- Logo / Header -->
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

            <!-- Records (collapsible group) -->
            @php
                $recordsActive = request()->routeIs('credits.index')
                              || request()->routeIs('discounts.index')
                              || request()->routeIs('totalizer.index');
            @endphp

            <li>
                <a class="sidebar-link {{ $recordsActive ? 'active' : '' }}"
                   data-bs-toggle="collapse"
                   href="#recordsSubmenu"
                   role="button"
                   aria-expanded="{{ $recordsActive ? 'true' : 'false' }}"
                   aria-controls="recordsSubmenu">
                    <i class="bi bi-archive sidebar-icon"></i>
                    <span class="nav-label d-flex align-items-center justify-content-between w-100">
                        Records
                        <i class="bi bi-chevron-down ms-auto records-chevron" style="font-size:12px; transition: transform 0.2s;"></i>
                    </span>
                </a>

                <div class="collapse {{ $recordsActive ? 'show' : '' }}" id="recordsSubmenu">
                    <ul class="nav flex-column gap-1 mt-1 ps-2 nav-label">

                        <!-- Credit Logs -->
                        <li>
                            <a href="{{ route('credits.index') }}"
                               class="sidebar-link {{ request()->routeIs('credits.index') ? 'active' : '' }}"
                               style="padding-left: 22px; font-size: 14px;">
                                <i class="bi bi-credit-card sidebar-icon"></i>
                                <span class="nav-label">Credit Logs</span>
                            </a>
                        </li>

                        <!-- Discount Logs -->
                        <li>
                            <a href="{{ route('discounts.index') }}"
                               class="sidebar-link {{ request()->routeIs('discounts.index') ? 'active' : '' }}"
                               style="padding-left: 22px; font-size: 14px;">
                                <i class="bi bi-tags sidebar-icon"></i>
                                <span class="nav-label">Discount Logs</span>
                            </a>
                        </li>

                        <!-- Totalizer Log -->
                        <li>
                            <a href="{{ route('totalizer.index') }}"
                               class="sidebar-link {{ request()->routeIs('totalizer.index') ? 'active' : '' }}"
                               style="padding-left: 22px; font-size: 14px;">
                                <i class="bi bi-speedometer2 sidebar-icon"></i>
                                <span class="nav-label">Totalizer Log</span>
                            </a>
                        </li>

                    </ul>
                </div>
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

<script>
    // Rotate chevron when Records submenu opens/closes
    const recordsSubmenu = document.getElementById('recordsSubmenu');
    const chevron = document.querySelector('.records-chevron');

    if (recordsSubmenu && chevron) {
        recordsSubmenu.addEventListener('show.bs.collapse', () => {
            chevron.style.transform = 'rotate(180deg)';
        });
        recordsSubmenu.addEventListener('hide.bs.collapse', () => {
            chevron.style.transform = 'rotate(0deg)';
        });

        // Set initial state
        if (recordsSubmenu.classList.contains('show')) {
            chevron.style.transform = 'rotate(180deg)';
        }
    }
</script>