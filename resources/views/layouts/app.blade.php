<!doctype html>
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
            background-color: #f4f9f5;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar {
            background: linear-gradient(to right, #4caf50, #66bb6a);
            border-bottom: 2px solid #81c784;
            padding: 10px 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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
        }

        .navbar .ml-auto {
            font-size: 1.1rem;
        }

        .navbar .navbar-brand i {
            font-size: 2rem;
        }

        .sidebar {
            background-color: #e8f5e9;
            height: 100vh;
            border-right: 2px solid #81c784;
            padding: 20px 10px;
        }

        .sidebar .btn {
            margin-bottom: 10px;
            text-align: left;
            transition: all 0.3s ease-in-out;
            position: relative;
        }

        .sidebar .btn:hover,
        .sidebar .btn.active {
            background-color: #388e3c !important;
            color: white !important;
            transform: translateX(5px);
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.3);
        }

        .sidebar .btn .badge {
            position: absolute;
            right: 15px;
            top: 8px;
            background-color: red;
            color: white;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.06);
            background-color: #ffffff;
            animation: fadeInUp 0.5s ease;
        }

        .card-header {
            font-weight: bold;
            background-color: #43a047;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
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
            background-color: #388e3c;
            color: white;
            text-align: center;
            padding: 10px 0;
            font-size: 14px;
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

        /* Smooth hover on icons */
        .sidebar .btn i {
            transition: transform 0.2s ease;
        }

        .sidebar .btn:hover i {
            transform: scale(1.15);
        }

        /* Responsive improvement */
        @media (max-width: 768px) {
            .sidebar {
                height: auto;
                padding-bottom: 20px;
            }

            footer {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">
            <i class="bi bi-capsule-fill mr-2"></i> Apotek Sehat Sentosa 💊
        </a>
        <div class="ml-auto dropdown">
            <a class="dropdown-toggle text-white d-flex align-items-center" href="#" role="button" id="adminDropdown"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                style="font-size: 1.2rem; font-weight: bold;">
                <i class="bi bi-person-circle mr-2" style="font-size: 1.5rem;"></i>
                Admin Apotek
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="adminDropdown">
                <a class="dropdown-item" href="{{ url('/logout') }}">
                    <i class="bi bi-box-arrow-right mr-2"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">

            <div class="col-md-2 sidebar">
                <a href="{{ url('/home') }}"
                    class="btn btn-success btn-block {{ request()->is('home') ? 'active' : '' }}">
                    <i class="bi bi-house-fill"></i> Home
                </a>
                <hr>
                <a href="{{ url('/datauseradmin') }}"
                    class="btn btn-outline-success btn-block {{ request()->is('datauseradmin') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i> Data User Admin
                </a>
                <a href="{{ url('/pembelian') }}"
                    class="btn btn-outline-success btn-block {{ request()->is('pembelian') ? 'active' : '' }}">
                    <i class="bi bi-cart-plus"></i> Pembelian
                </a>
                <a href="{{ url('/penjualan') }}"
                    class="btn btn-outline-success btn-block {{ request()->is('penjualan') ? 'active' : '' }}">
                    <i class="bi bi-cart-check"></i> Penjualan
                </a>
                <a href="{{ url('/kelolaobat') }}"
                    class="btn btn-outline-success btn-block {{ request()->is('kelolaobat') ? 'active' : '' }}">
                    <i class="bi bi-capsule"></i> Kelola Obat
                </a>
                <a href="{{ url('/stokopname') }}"
                    class="btn btn-outline-success btn-block {{ request()->is('stokopname') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Stok Opname
                </a>
                <a href="{{ url('/laporan') }}"
                    class="btn btn-outline-success btn-block {{ request()->is('laporan') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i> Laporan
                </a>
                <a href="{{ url('/logout') }}" class="btn btn-danger btn-block">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>


            <div class="col-md-10 py-4">
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
        new DataTable('#example');
    </script>
</body>

</html>
