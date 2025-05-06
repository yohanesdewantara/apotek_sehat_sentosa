<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1,<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2921/2921822.png" type="image/png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">

    <title>Apotek Sehat Sentosa - @yield('title')</title>

    <style>
        body {
            background-color: #f8fff9;
            font-family: 'Nunito', 'Segoe UI', sans-serif;
            padding-top: 60px;
            overflow-x: hidden;
        }

        .navbar {
            background: linear-gradient(135deg, #37a046, #66bb6a);
            border-bottom: 2px solid #81c784;
            padding: 12px 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            z-index: 1030;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
        }

        .navbar::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(40, 167, 69, 0.6);
            z-index: 1;
        }

        .navbar .navbar-brand,
        .navbar .ml-auto {
            z-index: 2;
            color: white !important;
            font-size: 1.8rem;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .navbar .ml-auto {
            font-size: 1.1rem;
        }

        .navbar .navbar-brand i {
            font-size: 2rem;
            filter: drop-shadow(1px 1px 2px rgba(0, 0, 0, 0.2));
        }

        /* Logo with animation */
        .navbar .navbar-brand .logo-pill {
            display: inline-block;
            animation: pulse 2s infinite;
            transform-origin: center;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        /* Sidebar styles */
        .sidebar {
            background: linear-gradient(180deg, #e8f5e9, #f1f8f2);
            height: calc(100vh - 60px);
            border-right: 2px solid #9ccc9f;
            padding: 20px 10px;
            position: fixed;
            top: 60px;
            left: 0;
            z-index: 1020;
            transition: all 0.3s ease;
            overflow-y: auto;
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.05);
        }

        /* Collapsed sidebar */
        .sidebar.collapsed {
            width: 70px !important;
        }

        .sidebar .btn {
            margin-bottom: 15px;
            text-align: left;
            transition: all 0.3s ease-in-out;
            position: relative;
            white-space: nowrap;
            overflow: hidden;
            border-radius: 12px;
            padding: 10px 15px;
            font-weight: 600;
            letter-spacing: 0.3px;
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
        }

        .sidebar.collapsed .btn span {
            display: none;
        }

        .sidebar.collapsed .btn {
            text-align: center;
            padding-left: 15px;
            padding-right: 15px;
        }

        .sidebar .btn:hover,
        .sidebar .btn.active {
            background-color: #2e7d32 !important;
            color: white !important;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.4);
        }

        .sidebar.collapsed .btn:hover,
        .sidebar.collapsed .btn.active {
            transform: translateX(0);
        }

        .sidebar .btn .badge {
            position: absolute;
            right: 15px;
            top: 8px;
            background-color: #f44336;
            color: white;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* Button separator line */
        .sidebar hr {
            margin: 15px 0;
            border-color: #9ccc9f;
            opacity: 0.5;
        }

        /* Toggle button */
        #sidebar-toggle {
            cursor: pointer;
            margin-right: 15px;
            font-size: 1.5rem;
            color: white;
            z-index: 3;
            transition: transform 0.3s ease;
        }

        #sidebar-toggle:hover {
            transform: scale(1.15);
        }

        /* Main content */
        .main-content {
            transition: all 0.3s ease;
            padding-top: 20px;
            padding-bottom: 50px; /* Space for footer */
        }

        .main-content.expanded {
            margin-left: 70px !important;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            background-color: #ffffff;
            animation: fadeInUp 0.5s ease;
            border: none;
            margin-bottom: 25px;
            overflow: hidden;
        }

        .card-header {
            font-weight: bold;
            background: linear-gradient(135deg, #37a046, #4caf50);
            color: white;
            border-top-left-radius: 15px !important;
            border-top-right-radius: 15px !important;
            padding: 15px 20px;
            font-size: 1.2rem;
            letter-spacing: 0.5px;
            border: none;
        }

        .card-body {
            padding: 25px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background: linear-gradient(135deg, #2e7d32, #388e3c);
            color: white;
            text-align: center;
            padding: 12px 0;
            font-size: 14px;
            box-shadow: 0 -3px 10px rgba(0, 0, 0, 0.1);
            z-index: 1010;
        }

        .breadcrumb {
            background-color: transparent;
            padding-left: 0;
        }

        .breadcrumb-item a {
            color: #388e3c;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: #2e7d32;
            text-decoration: underline;
        }

        .breadcrumb-item.active {
            color: #1b5e20;
        }

        .sidebar .btn-danger {
            background-color: #e53935 !important;
            color: white !important;
            box-shadow: none !important;
        }

        .sidebar .btn-danger:hover,
        .sidebar .btn-danger:active {
            background-color: #e53935 !important;
            color: white !important;
            transform: none !important;
            box-shadow: none !important;
        }

        .sidebar .btn i {
            transition: transform 0.2s ease;
            margin-right: 10px;
        }

        .sidebar.collapsed .btn i {
            margin-right: 0;
        }

        .sidebar .btn:hover i {
            transform: scale(1.15);
        }

        /* Additional styling elements */
        .highlight-row {
            animation: highlightRow 0.5s ease;
        }

        @keyframes highlightRow {
            0% { background-color: rgba(76, 175, 80, 0.2); }
            100% { background-color: transparent; }
        }

        /* Customized DataTable styling */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #9ccc9f;
            border-radius: 10px;
            padding: 8px 12px;
            margin-left: 10px;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #9ccc9f;
            border-radius: 10px;
            padding: 6px 10px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 8px !important;
            margin: 0 3px;
            border: 1px solid #9ccc9f !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #37a046, #4caf50) !important;
            color: white !important;
            border: 1px solid #2e7d32 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e8f5e9 !important;
            color: #2e7d32 !important;
        }

        /* Card hover effect */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        }

        /* Custom scrollbar for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #e8f5e9;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #9ccc9f;
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #81c784;
        }

        /* Tooltip for collapsed sidebar */
        .sidebar.collapsed .btn:hover::after {
            content: attr(data-title);
            position: absolute;
            left: 100%;
            top: 0;
            background: #2e7d32;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            white-space: nowrap;
            z-index: 1040;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            font-weight: 500;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Google Font - Nunito */
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap');

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: fixed;
                top: 60px;
                left: 0;
                right: 0;
                padding-bottom: 20px;
                z-index: 1020;
            }

            .sidebar.collapsed {
                width: 100% !important;
                padding: 10px;
                transform: translateY(-100%);
                top: 60px;
            }

            .sidebar.collapsed .btn span {
                display: inline;
            }

            .main-content {
                margin-left: 0 !important;
                margin-top: 60px;
                padding-top: 50px;
            }

            .main-content.expanded {
                margin-left: 0 !important;
            }

            body.sidebar-open {
                overflow: hidden;
            }

            footer {
                font-size: 12px;
                padding: 10px 0;
            }

            #mobile-toggle {
                display: block;
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 1050;
                border-radius: 50%;
                width: 55px;
                height: 55px;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                background: linear-gradient(135deg, #2e7d32, #388e3c) !important;
                border: none;
                animation: float 2s infinite ease-in-out;
            }

            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-8px); }
                100% { transform: translateY(0px); }
            }

            #mobile-toggle span {
                display: none;
            }

            #mobile-toggle i {
                font-size: 1.5rem;
                margin: 0;
                color: white;
            }

            #desktop-toggle {
                display: none;
            }

            .navbar .navbar-brand {
                font-size: 1.4rem;
            }

            .navbar .ml-auto {
                font-size: 1rem;
            }
        }

        @media (min-width: 769px) {
            #mobile-toggle {
                display: none;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg">
        <div id="sidebar-toggle">
            <i class="bi bi-list"></i>
        </div>
        <a class="navbar-brand" href="#">
            <i class="bi bi-capsule-fill mr-2 logo-pill"></i> Apotek Sehat Sentosa <span class="d-none d-sm-inline">💊</span>
        </a>
        <div class="ml-auto dropdown">
            <a class="dropdown-toggle text-white d-flex align-items-center" href="#" role="button" id="adminDropdown"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                style="font-size: 1.2rem; font-weight: bold;">
                <i class="bi bi-person-circle mr-2" style="font-size: 1.5rem;"></i>
                <span class="d-none d-sm-inline">Admin Apotek</span>
                <span class="d-inline d-sm-none">Admin</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="adminDropdown"
                style="border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.15);">
                <a class="dropdown-item" href="{{ url('/profile') }}">
                    <i class="bi bi-person mr-2"></i> Profile
                </a>
                <a class="dropdown-item" href="{{ url('/settings') }}">
                    <i class="bi bi-gear mr-2"></i> Settings
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ url('/logout') }}">
                    <i class="bi bi-box-arrow-right mr-2"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar" id="sidebar">
                <!-- Mobile toggle button is now moved outside the sidebar for better UX -->
                <button id="mobile-toggle" class="btn btn-success">
                    <i class="bi bi-arrows-collapse"></i>
                </button>
                <a href="{{ url('/home') }}"
                    class="btn btn-success btn-block {{ request()->is('home') ? 'active' : '' }}"
                    data-title="Home">
                    <i class="bi bi-house-fill"></i> <span>Home</span>
                </a>
                <hr>
                <a href="{{ url('/datauseradmin') }}"
                    class="btn btn-outline-success btn-block {{ request()->is('datauseradmin') ? 'active' : '' }}"
                    data-title="Data User Admin">
                    <i class="bi bi-person-gear"></i> <span>Data User Admin</span>
                </a>
                <a href="{{ url('/pembelian') }}"
                    class="btn btn-outline-success btn-block {{ request()->is('pembelian') ? 'active' : '' }}"
                    data-title="Pembelian">
                    <i class="bi bi-cart-plus"></i> <span>Pembelian</span>
                </a>
                <a href="{{ url('/penjualan') }}"
                    class="btn btn-outline-success btn-block {{ request()->is('penjualan') ? 'active' : '' }}"
                    data-title="Penjualan">
                    <i class="bi bi-cart-check"></i> <span>Penjualan</span>
                </a>
                <a href="{{ url('/kelolaobat') }}"
                    class="btn btn-outline-success btn-block {{ request()->is('kelolaobat') ? 'active' : '' }}"
                    data-title="Kelola Obat">
                    <i class="bi bi-capsule"></i> <span>Kelola Obat</span>
                </a>
                <a href="{{ url('/stokopname') }}"
                    class="btn btn-outline-success btn-block {{ request()->is('stokopname') ? 'active' : '' }}"
                    data-title="Stok Opname">
                    <i class="bi bi-box-seam"></i> <span>Stok Opname</span>
                </a>
                <a href="{{ url('/laporan') }}"
                    class="btn btn-outline-success btn-block {{ request()->is('laporan') ? 'active' : '' }}"
                    data-title="Laporan">
                    <i class="bi bi-file-earmark-text"></i> <span>Laporan</span>
                </a>
                <a href="{{ url('/logout') }}" class="btn btn-danger btn-block" data-title="Logout">
                    <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                </a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 offset-md-2 py-4 main-content" id="main-content">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                    </ol>
                </nav>

                <div class="card">
                    <div class="card-header">
                        @yield('title')
                    </div>
                    <div class="card-body">
                        @yield('artikel')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        © 2025 Apotek Sehat Sentosa
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
    <script>
        // Initialize DataTable
        new DataTable('#example');

        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const mobileToggle = document.getElementById('mobile-toggle');
            const body = document.body;

            // Function to adjust content based on window size
            function adjustForScreenSize() {
                if (window.innerWidth <= 768) {
                    // Mobile view adjustments
                    mainContent.style.marginTop = sidebar.classList.contains('collapsed') ? '0' : sidebar.offsetHeight + 'px';
                } else {
                    // Desktop view adjustments
                    mainContent.style.marginTop = '0';
                }
            }

            // Check localStorage for saved state
            const sidebarState = localStorage.getItem('sidebarCollapsed');
            if (sidebarState === 'true') {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                if (mobileToggle) {
                    mobileToggle.innerHTML = '<i class="bi bi-arrows-expand"></i>';
                }
            }

            // Initial adjustment
            adjustForScreenSize();

            // Adjust on resize
            window.addEventListener('resize', adjustForScreenSize);

            // Desktop toggle
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                body.classList.toggle('sidebar-open');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));

                // Update mobile toggle button icon
                if (mobileToggle) {
                    if (sidebar.classList.contains('collapsed')) {
                        mobileToggle.innerHTML = '<i class="bi bi-arrows-expand"></i>';
                    } else {
                        mobileToggle.innerHTML = '<i class="bi bi-arrows-collapse"></i>';
                    }
                }

                // Adjust content after toggle
                adjustForScreenSize();
            });

            // Mobile toggle
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                    body.classList.toggle('sidebar-open');
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));

                    if (sidebar.classList.contains('collapsed')) {
                        mobileToggle.innerHTML = '<i class="bi bi-arrows-expand"></i>';
                    } else {
                        mobileToggle.innerHTML = '<i class="bi bi-arrows-collapse"></i>';
                    }

                    // Adjust content after toggle
                    adjustForScreenSize();
                });
            }

            // Close sidebar when clicking on a link (mobile only)
            if (window.innerWidth <= 768) {
                const sidebarLinks = sidebar.querySelectorAll('a.btn');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        sidebar.classList.add('collapsed');
                        mainContent.classList.add('expanded');
                        body.classList.remove('sidebar-open');
                        localStorage.setItem('sidebarCollapsed', 'true');
                        if (mobileToggle) {
                            mobileToggle.innerHTML = '<i class="bi bi-arrows-expand"></i>';
                        }
                        adjustForScreenSize();
                    });
                });
            }
        });
    </script>
</body>

</html>
