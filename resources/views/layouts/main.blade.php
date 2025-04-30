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
    padding-top: 70px; /* Tinggi navbar */
    padding-left: 250px; /* Lebar sidebar */
}

.navbar {
    background: linear-gradient(to right, #4caf50, #66bb6a);
    border-bottom: 2px solid #81c784;
    padding: 10px 30px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
}

.sidebar {
    background-color: #e8f5e9;
    height: 100vh;
    width: 250px;
    position: fixed;
    top: 70px; /* agar tidak tertutup navbar */
    left: 0;
    padding: 20px 10px;
    border-right: 2px solid #81c784;
    z-index: 999;
    overflow-y: auto;
}

.main-content {
    margin-left: 250px;
    padding: 20px;
}

@media (max-width: 768px) {
    .sidebar {
        width: 200px;
    }

    body {
        padding-left: 200px;
    }

    .main-content {
        margin-left: 200px;
    }
}


        @media (max-width: 768px) {
            .sidebar {
                height: auto;
                width: 200px; /* Lebar sidebar lebih kecil di mobile */
                padding-bottom: 20px;
            }

            .main-content {
                margin-left: 200px;
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

            <!-- Sidebar -->
            <div class="sidebar">
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

            <!-- Main content -->
            <div class="main-content">
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
