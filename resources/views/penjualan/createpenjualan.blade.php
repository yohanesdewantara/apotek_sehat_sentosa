@extends('layouts.main')
@section('title', 'Tambah Penjualan')
@section('artikel')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #4e73df;
            color: white;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }
        .kasir-container {
            display: flex;
            gap: 20px;
        }
        .order-section {
            flex: 2;
        }
        .payment-section {
            flex: 1;
        }
        .product-row {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        .product-row:hover {
            background-color: #f8f9fc;
        }
        .product-input {
            padding: 15px;
            background-color: #f8f9fc;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .badge-discount {
            background-color: #e74a3b;
            color: white;
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 50px;
        }
        .product-list {
            max-height: 400px;
            overflow-y: auto;
        }
        .btn-kasir {
            padding: 12px 20px;
            font-weight: bold;
        }
        .total-section {
            background-color: #f8f9fc;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .grand-total {
            font-size: 24px;
            font-weight: bold;
            color: #4e73df;
        }
        .transaction-info {
            padding: 20px;
            background-color: #f8f9fc;
            border-radius: 10px;
        }
        .remove-product {
            cursor: pointer;
            color: #e74a3b;
        }
        .remove-product:hover {
            transform: scale(1.2);
        }
        .input-with-icon {
            position: relative;
        }
        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #858796;
        }
        .input-with-icon input {
            padding-left: 40px;
        }
    </style>

    <form action="{{ route('penjualan.store') }}" method="POST" id="penjualan-form">
        @csrf

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header py-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold">
                                    <i class="fas fa-cash-register mr-2"></i>SISTEM KASIR PENJUALAN OBAT
                                </h6>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('penjualan.index') }}" class="btn btn-sm btn-outline-light">
                                    <i class="fas fa-list mr-1"></i>Daftar Transaksi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="kasir-container">
            <!-- Left Section: Products -->
            <div class="order-section">
                <div class="card">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-shopping-cart mr-2"></i>PESANAN
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Product Search & Add -->
                        <div class="product-input">
                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <label for="nama-obat" class="form-label">Nama Obat</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-search"></i>
                                        <input list="obat-list" id="main-obat-input" class="form-control nama-obat" placeholder="Ketik atau scan obat">
                                    </div>
                                    <datalist id="obat-list">
                                        @foreach($obats as $obat)
                                            <option value="{{ $obat->nama_obat }}"></option>
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="col-md-2">
                                    <label for="jumlah" class="form-label">Jumlah</label>
                                    <input type="number" id="main-jumlah-input" class="form-control" value="1" min="1">
                                </div>
                                <div class="col-md-4">
                                    <button type="button" id="add-to-cart" class="btn btn-primary btn-block btn-kasir">
                                        <i class="fas fa-plus mr-2"></i>Tambah
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Products List -->
                        <div class="product-list">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Obat</th>
                                            <th class="text-center">Harga</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail-obat-wrapper">
                                        <!-- Products will be added here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Total Section -->
                        <div class="total-section mt-4">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h5>Total Item: <span id="total-items">0</span></h5>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h5>Grand Total:</h5>
                                    <div class="grand-total">Rp <span id="grand-total">0</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Section: Payment Info -->
            <div class="payment-section">
                <div class="card">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-file-invoice mr-2"></i>INFORMASI TRANSAKSI
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="transaction-info">
                            <div class="form-group">
                                <label for="id_penjualan">Nomor Transaksi</label>
                                <input type="text" name="id_penjualan" id="id_penjualan" class="form-control" value="{{ $nextId }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="tgl_penjualan">Tanggal</label>
                                <input type="date" name="tgl_penjualan" id="tgl_penjualan" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="id_admin">Kasir</label>
                                <select name="id_admin" id="id_admin" class="form-control" required>
                                    @foreach($admins as $admin)
                                        <option value="{{ $admin->id_admin }}">{{ $admin->nama_admin }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="button" id="clear-transaction" class="btn btn-outline-danger btn-block mb-3">
                                <i class="fas fa-trash-alt mr-2"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-success btn-block btn-kasir">
                                <i class="fas fa-save mr-2"></i>Simpan Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Set today's date as default
            document.getElementById('tgl_penjualan').value = new Date().toISOString().split('T')[0];

            const obatData = @json($obats);
            let rowCounter = 0;
            const detailObatWrapper = document.getElementById('detail-obat-wrapper');

            // Function to update totals
            function updateTotals() {
                let grandTotal = 0;
                let totalItems = 0;

                document.querySelectorAll('.detail-obat-item').forEach(item => {
                    const subtotal = parseFloat(item.querySelector('.subtotal').dataset.value) || 0;
                    const qty = parseInt(item.querySelector('.jumlah-terjual').value) || 0;

                    grandTotal += subtotal;
                    totalItems += qty;
                });

                document.getElementById('grand-total').textContent = formatNumber(grandTotal);
                document.getElementById('total-items').textContent = totalItems;
            }

            // Add product to cart
            document.getElementById('add-to-cart').addEventListener('click', function() {
                const namaObat = document.getElementById('main-obat-input').value.trim();
                const jumlah = parseInt(document.getElementById('main-jumlah-input').value) || 1;

                if (!namaObat) {
                    alert('Silakan pilih obat terlebih dahulu!');
                    return;
                }

                const found = obatData.find(obat => obat.nama_obat.toLowerCase() === namaObat.toLowerCase());

                if (!found) {
                    alert('Obat tidak ditemukan dalam database!');
                    return;
                }

                addProductToCart(found.id_obat, namaObat, jumlah);

                // Clear inputs
                document.getElementById('main-obat-input').value = '';
                document.getElementById('main-jumlah-input').value = '1';
                document.getElementById('main-obat-input').focus();
            });

            // Add product to cart function
            function addProductToCart(idObat, namaObat, jumlah) {
                rowCounter++;

                // First check if the product is already in the cart
                const existingRow = Array.from(detailObatWrapper.querySelectorAll('.detail-obat-item')).find(
                    row => row.querySelector('.id-obat').value === idObat
                );

                if (existingRow) {
                    // Update quantity instead of adding new row
                    const qtyInput = existingRow.querySelector('.jumlah-terjual');
                    const currentQty = parseInt(qtyInput.value) || 0;
                    qtyInput.value = currentQty + jumlah;

                    // Update subtotal
                    const harga = parseFloat(existingRow.querySelector('.harga-jual').dataset.value) || 0;
                    const subtotalElem = existingRow.querySelector('.subtotal');
                    const newSubtotal = harga * (currentQty + jumlah);
                    subtotalElem.textContent = formatNumber(newSubtotal);
                    subtotalElem.dataset.value = newSubtotal;

                    updateTotals();
                    return;
                }

                // Fetch product details including discount
                fetch(`/obat/info/${idObat}`)
                    .then(response => response.json())
                    .then(data => {
                        const row = document.createElement('tr');
                        row.className = 'detail-obat-item product-row';

                        const harga = data.discounted_price;
                        const subtotal = harga * jumlah;
                        const hasDiscount = data.has_discount;

                        // Create row HTML
                        row.innerHTML = `
                            <td>
                                <div class="font-weight-bold">${namaObat}</div>
                                <input type="hidden" name="id_obat[]" class="id-obat" value="${idObat}">
                                ${hasDiscount ?
                                    `<span class="badge-discount">
                                        <i class="fas fa-tag mr-1"></i>Diskon ${data.discount_percent}%
                                    </span>
                                    <div class="small text-muted">Harga Normal: Rp ${formatNumber(data.regular_price)}</div>`
                                    : ''}
                            </td>
                            <td class="text-right">
                                <div>Rp ${formatNumber(harga)}</div>
                                <input type="hidden" name="harga_jual[]" class="harga-jual" value="${harga}" data-value="${harga}">
                            </td>
                            <td class="text-center">
                                <input type="number" name="jumlah_terjual[]" class="form-control form-control-sm jumlah-terjual" value="${jumlah}" min="1">
                            </td>
                            <td class="text-right">
                                <div class="font-weight-bold">Rp <span class="subtotal" data-value="${subtotal}">${formatNumber(subtotal)}</span></div>
                            </td>
                            <td class="text-center">
                                <i class="fas fa-times remove-product"></i>
                            </td>
                        `;

                        // Add event listener to quantity input
                        const qtyInput = row.querySelector('.jumlah-terjual');
                        qtyInput.addEventListener('change', function() {
                            const harga = parseFloat(row.querySelector('.harga-jual').dataset.value) || 0;
                            const jumlah = parseInt(this.value) || 0;
                            const subtotalElem = row.querySelector('.subtotal');
                            const subtotal = harga * jumlah;

                            subtotalElem.textContent = formatNumber(subtotal);
                            subtotalElem.dataset.value = subtotal;

                            updateTotals();
                        });

                        // Add event listener to remove button
                        row.querySelector('.remove-product').addEventListener('click', function() {
                            row.remove();
                            updateTotals();
                        });

                        detailObatWrapper.appendChild(row);
                        updateTotals();
                    })
                    .catch(error => {
                        console.error('Error fetching obat info:', error);
                        alert('Gagal mengambil informasi obat. Silakan coba lagi.');
                    });
            }

            // Clear transaction
            document.getElementById('clear-transaction').addEventListener('click', function() {
                if (confirm('Apakah Anda yakin ingin membatalkan transaksi ini?')) {
                    detailObatWrapper.innerHTML = '';
                    updateTotals();
                }
            });

            // Form submission
            document.getElementById('penjualan-form').addEventListener('submit', function(event) {
                const items = document.querySelectorAll('.detail-obat-item');

                if (items.length === 0) {
                    event.preventDefault();
                    alert('Transaksi tidak dapat disimpan karena keranjang kosong!');
                    return false;
                }

                return true;
            });

            // Helper function to format number to Indonesian currency format
            function formatNumber(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }

            // Enable autocomplete for main product input
            $('#main-obat-input').autocomplete({
                source: obatData.map(item => item.nama_obat),
                minLength: 2,
            });
        });
    </script>
@endsection
