<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Seal Gasoline Station</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
        }

        /* ── Sidebar ── */
        #sidebar {
            width: 240px;
            min-height: 100vh;
            background-color: #fff;
            flex-shrink: 0;
            transition: width 0.25s ease;
            overflow: hidden;
        }

        #sidebar.collapsed {
            width: 68px;
        }

        /* Hide text labels when collapsed */
        #sidebar.collapsed .nav-label {
            display: none;
        }

        /* Hide the toggle button chevron direction */
        #sidebar.collapsed #sidebarToggle i {
            transform: scaleX(-1);
        }

        /* Nav links */
        .sidebar-link {
            color: #444;
            font-size: 15px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 12px;
            transition: background 0.15s, color 0.15s;
            white-space: nowrap;
            cursor: pointer;
        }

        .sidebar-link {
            text-decoration: none !important;
        }

        #sidebar .border-bottom {
            border-bottom: none !important;
        }

        .sidebar-link:hover {
            background-color: #fff0f2;
            color: #D2042D;
        }

        .sidebar-link.active {
            background-color: #D2042D;
            color: #fff !important;
        }

        .sidebar-link.active .sidebar-icon {
            color: #fff;
        }

        .sidebar-icon {
            font-size: 18px;
            flex-shrink: 0;
            width: 20px;
            text-align: center;
            color: #888;
            transition: color 0.15s;
        }

        .sidebar-link:hover .sidebar-icon {
            color: #D2042D;
        }

        /* Collapsed: center icons */
        #sidebar.collapsed .sidebar-link {
            justify-content: center;
            padding: 11px 0;
        }

        /* ── Main content ── */
        #main-content {
            flex: 1;
            overflow-x: hidden;
            background-color: #f8f9fa;
        }

        /* ── Customer page buttons ── */
        #customer .btn-primary {
            background-color: #D2042D;
            border: none;
            border-radius: 15px;
            height: 50px;
            padding: 0 20px;
        }

        #customer .btn-primary:hover {
            background-color: #FC5F7E;
        }

        html { scroll-behavior: smooth; }
        .name { width: 400px; }
    </style>

    {{-- ↓ THIS LINE was missing — it outputs any @push('styles') from child views --}}
    @stack('styles')
</head>
<body>

    @include('partials.sidebar')

    <div id="main-content" class="p-4">
        @yield('content')
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }

        // Restore sidebar state on load
        (function () {
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                document.getElementById('sidebar').classList.add('collapsed');
            }
        })();
    </script>

    @stack('scripts')
</body>
</html>