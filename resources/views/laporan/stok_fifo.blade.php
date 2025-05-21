@extends('layouts.main')

@section('title', 'Laporan Stok FIFO')

@section('artikel')
    <div class="container-fluid">
        <!-- Header Apotek -->
        <div class="text-center mb-3 print-only">
            <h2 class="mb-1">APOTEK SEHAT SENTOSA</h2>
            <p class="mb-0">Jl. Prof Yohanes No. 123, Yogyakarta</p>
            <p>Telp: 0831 3869 4411 | Email: apoteksehatsentosa@gmail.com</p>
            <hr class="my-2">
        </div>

        <div class="mb-3 d-flex justify-content-between align-items-center no-print">
            <a href="{{ route('laporan.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <div>
                <button class="btn btn-sm btn-success" onclick="printTable()">
                    <i class="bi bi-printer"></i> Cetak
                </button>
                <button class="btn btn-sm btn-primary" id="exportExcel">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </button>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Data Stok Obat FIFO</span>
                    <span>Tanggal: {{ date('d-m-Y') }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="stokFifoTable">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Obat</th>
                                <th>Jenis</th>
                                <th>Lokasi Rak</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Stok</th>
                                <th>Tgl Kadaluarsa</th>
                                <th>Status</th> <!-- Removed no-print class -->
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                                $today = \Carbon\Carbon::today();
                                $totalStokKeseluruhan = 0;
                            @endphp

                            @forelse($groupedStok as $id_obat => $items)
                                @php
                                    $firstItem = $items->first();
                                    $totalStok = $items->sum('stok');
                                    $rowspan = $items->count();
                                    $totalStokKeseluruhan += $totalStok;
                                @endphp

                                @foreach($items as $index => $item)
                                    <tr>
                                        @if($index === 0)
                                            <td rowspan="{{ $rowspan }}">{{ $no++ }}</td>
                                            <td rowspan="{{ $rowspan }}">{{ $firstItem->nama_obat }}</td>
                                            <td rowspan="{{ $rowspan }}">{{ $firstItem->jenis_obat ?: '-' }}</td>
                                            <td rowspan="{{ $rowspan }}">{{ $firstItem->nama_rak ?: '-' }}</td>
                                            <td rowspan="{{ $rowspan }}">Rp {{ number_format($firstItem->harga_beli, 0, ',', '.') }}
                                            </td>
                                            <td rowspan="{{ $rowspan }}">Rp {{ number_format($firstItem->harga_jual, 0, ',', '.') }}
                                            </td>
                                            <td rowspan="{{ $rowspan }}">{{ $totalStok }}</td>
                                        @endif

                                        <td>{{ \Carbon\Carbon::parse($item->tgl_kadaluarsa)->format('d-m-Y') }}</td>
                                        <td> <!-- Removed no-print class -->
                                            @php
                                                $expDate = \Carbon\Carbon::parse($item->tgl_kadaluarsa);
                                                $diffDays = $today->diffInDays($expDate, false);
                                                $isStokOpname = !empty($item->id_detailopname);
                                            @endphp

                                            @if($isStokOpname)
                                                <span class="badge bg-danger">Kadaluarsa (Stok Opname)</span>
                                                <span class="ms-1 badge bg-light text-dark">
                                                    Stok: {{ $item->stok_kadaluarsa ?: $item->stok }}
                                                </span>
                                            @elseif($diffDays < 0)
                                                <span class="badge bg-danger">Kadaluarsa</span>
                                                <span class="ms-1 badge bg-light text-dark">
                                                    Stok: {{ $item->stok }}
                                                </span>
                                            @elseif($diffDays <= 30)
                                                <span class="badge bg-warning text-dark">{{ $diffDays }} hari lagi</span>
                                                <span class="ms-1 badge bg-light text-dark">
                                                    Stok: {{ $item->stok }}
                                                </span>
                                            @elseif($diffDays <= 90)
                                                <span class="badge bg-info">{{ $diffDays }} hari lagi</span>
                                                <span class="ms-1 badge bg-light text-dark">
                                                    Stok: {{ $item->stok }}
                                                </span>
                                            @else
                                                <span class="badge bg-success">{{ $diffDays }} hari lagi</span>
                                                <span class="ms-1 badge bg-light text-dark">
                                                    Stok: {{ $item->stok }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data stok obat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-end"><strong>Total Stok Keseluruhan:</strong></td>
                                <td><strong>{{ $totalStokKeseluruhan }}</strong></td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mt-4 no-print">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-light">
                        <i class="bi bi-exclamation-triangle me-2"></i> Peringatan Stok Kadaluarsa
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Indikator</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="badge bg-danger">Kadaluarsa</span></td>
                                        <td>Produk sudah kadaluarsa</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-warning text-dark">X hari lagi</span></td>
                                        <td>Produk akan kadaluarsa dalam 30 hari</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-info">X hari lagi</span></td>
                                        <td>Produk akan kadaluarsa dalam 31-90 hari</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-success">X hari lagi</span></td>
                                        <td>Produk masih lama kadaluarsa (>90 hari)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <i class="bi bi-info-circle me-2"></i> Informasi FIFO
                    </div>
                    <div class="card-body">
                        <p>
                            <strong>First In First Out (FIFO)</strong> adalah metode pengelolaan persediaan di mana
                            produk/barang yang pertama masuk akan menjadi yang pertama keluar. Prinsip ini penting untuk
                            apotek karena:
                        </p>
                        <ul>
                            <li>Memastikan obat tidak kadaluarsa di rak</li>
                            <li>Mengurangi kerugian akibat barang yang tidak terjual sebelum tanggal kadaluarsa</li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tanda tangan/Footer yang muncul di cetak -->
        <div class="row mt-5 print-only">
            <div class="col-4"></div>
            <div class="col-8 text-center">
                <p class="mb-5">{{ date('d F Y') }}</p>
                <p class="mt-5"><strong>Admin Apotek</strong></p>
                <p class="mt-4">_________________________</p>
            </div>
        </div>
    </div>

    <!-- Print-only table styling -->
    <style>
        /* Sembunyikan elemen untuk tampilan non-cetak */
        .print-only {
            display: none;
        }

        /* Basic badge styling for screen view */
        .badge {
            display: inline-block;
            padding: 0.25em 0.6em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }

        @media print {

            /* Reset the page */
            @page {
                size: A4;
                margin: 1cm;
            }

            /* Tampilkan elemen print-only */
            .print-only {
                display: block;
            }

            /* Hide everything by default */
            body * {
                visibility: hidden;
            }

            /* Override to show only what we want and preserve colors */
            .container-fluid,
            .print-only,
            .print-only *,
            .container-fluid .card,
            .container-fluid .card-header,
            .container-fluid .card-header *,
            .container-fluid .card-body,
            .container-fluid .table-responsive,
            #stokFifoTable,
            #stokFifoTable *,
            .badge {
                visibility: visible;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            /* Hide elements with no-print class */
            .no-print {
                display: none !important;
            }

            /* Create a clean print layout */
            body {
                margin: 0;
                padding: 0;
                line-height: 1.4;
                font-family: "Arial", "Helvetica", sans-serif;
                color: #000;
                background: #fff;
            }

            /* Professional positioning */
            .container-fluid {
                position: relative;
                width: 100%;
                padding: 0;
                margin: 0;
            }

            /* Reset card styles for printing */
            .card {
                border: none !important;
                box-shadow: none !important;
                position: relative;
                display: block;
                width: 100%;
                padding: 0;
                margin: 0;
            }

            /* Style the report subheader */
            .card-header {
                background-color: #fff !important;
                color: #000 !important;
                border-bottom: 1px solid #000;
                padding: 10px 0;
                margin-bottom: 15px;
                text-align: center;
            }

            /* Center and format the header content */
            .card-header .d-flex {
                display: block !important;
                justify-content: center !important;
                text-align: center;
            }

            /* Format main title text */
            .card-header .d-flex span:first-child {
                display: block;
                font-weight: bold;
                font-size: 16pt;
                margin-bottom: 5px;
            }

            /* Icon styling in header */
            .card-header .bi-box-seam {
                display: inline-block;
                margin-right: 8px;
            }

            /* Date format in header */
            .card-header .d-flex span:last-child {
                display: block;
                font-size: 12pt;
                font-style: italic;
                margin-top: 3px;
            }

            /* Add space and clean styling to the table container */
            .card-body {
                padding: 0;
            }

            /* Table styling for professional appearance */
            .table-responsive {
                margin-top: 10px;
                overflow: visible;
            }

            /* Make the table clean and professional */
            .table {
                width: 100% !important;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            /* Header cells styling */
            .table thead th {
                background-color: #f1f1f1 !important;
                color: #000 !important;
                font-weight: bold;
                font-size: 10pt;
                text-align: center;
                padding: 8px;
                border: 1px solid #000;
            }

            /* Table cell styling */
            .table tbody td {
                padding: 6px;
                border: 1px solid #000;
                font-size: 10pt;
                vertical-align: middle;
            }

            /* Alternating row colors */
            .table tbody tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            /* Footer with page numbers */
            .table+p.print-footer {
                display: block;
                text-align: center;
                font-size: 9pt;
                margin-top: 20px;
                padding-top: 10px;
                font-style: italic;
            }

            /* Page break settings */
            .card {
                page-break-before: auto;
                page-break-after: auto;
            }

            /* Ensure content is not cut off */
            .table tr {
                page-break-inside: avoid;
            }

            /* Header on each page when table breaks */
            thead {
                display: table-header-group;
            }

            /* Footer on each page */
            tfoot {
                display: table-footer-group;
            }

            /* Style for badges when printing - with color preservation */
            .badge {
                padding: 2px 5px;
                font-size: 9pt !important;
                font-weight: normal;
                display: inline-block;
                border: none;
                color: #fff !important;
            }

            .badge.bg-danger {
                background-color: #dc3545 !important;
                color: #fff !important;
            }

            .badge.bg-warning {
                background-color: #ffc107 !important;
                color: #000 !important;
            }

            .badge.bg-info {
                background-color: #0dcaf0 !important;
                color: #000 !important;
            }

            .badge.bg-success {
                background-color: #198754 !important;
                color: #fff !important;
            }

            .badge.bg-light {
                background-color: #f8f9fa !important;
                color: #000 !important;
                border: 1px solid #dee2e6;
            }
        }
    </style>

    <script>
        // Initialize DataTable when DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function () {
            new DataTable('#stokFifoTable', {
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            columns: ':visible' // Changed from ':not(.no-print)' to include all visible columns
                        }
                    }
                ]
            });

            // Export to Excel functionality
            document.getElementById('exportExcel').addEventListener('click', function () {
                const table2excel = new Table2Excel();
                table2excel.export(document.getElementById('stokFifoTable'), 'Laporan_Stok_FIFO_' + new Date().toISOString().slice(0, 10));
            });
        });

        // Custom print function
        function printTable() {
            window.print();
        }
    </script>
@endsection
