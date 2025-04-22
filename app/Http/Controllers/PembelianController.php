<?php

    namespace App\Http\Controllers;

    use App\Models\DetailPembelian;
    use App\Models\Pembelian;
    use App\Models\DetailObat;
    use Illuminate\Http\Request;

    class PembelianController extends Controller
    {
        public function index()
        {
            $data = Pembelian::with('detailPembelian.detailObat.obat')->get();
            return view('pembelian.pembelian', compact('data'));
        }

        public function create()
        {
            $detailObats = DetailObat::with('obat')->get();
            return view('pembelian.createpembelian', compact('detailObats'));
        }

        public function store(Request $request)
    {
        $request->validate([
            'nama_admin'     => 'required|string|max:255',
            'tgl_pembelian'  => 'required|date',
            'nama_obat'      => 'required|string|max:255',
            'jenis_obat'     => 'required|string|max:255',
            'keterangan_obat'=> 'required|string|max:255',
            'jumlah_beli'    => 'required|integer|min:1',
            'harga_beli'     => 'required|numeric|min:0',
            'harga_jual'     => 'required|numeric|min:0',
            'tgl_kadaluarsa' => 'required|date',
        ]);

        // 1. Simpan ke tabel obat
        $obat = \App\Models\Obat::create([
            'id_rak'           => 1, // atau ambil default rak, nanti bisa diatur
            'nama_obat'        => $request->nama_obat,
            'stok_total'       => $request->jumlah_beli, // stok total awal = jumlah beli
            'keterangan_obat'  => $request->keterangan_obat,
            'jenis_obat'       => $request->jenis_obat,
            'harga_beli'       => $request->harga_beli,
            'harga_jual'       => $request->harga_jual,
        ]);

        // 2. Simpan ke tabel detail_obat
        $detailObat = \App\Models\DetailObat::create([
            'id_obat'      => $obat->id_obat,
            'stok'         => $request->jumlah_beli,
            'tgl_kadaluarsa'=> $request->tgl_kadaluarsa,
        ]);

        // 3. Hitung total harga
        $total_harga = $request->jumlah_beli * $request->harga_beli;

        // 4. Simpan ke tabel pembelian
        $pembelian = Pembelian::create([
            'tgl_pembelian' => $request->tgl_pembelian,
            'total'         => $total_harga,
            'id_admin'      => auth()->user()->id ?? 1, // fallback admin id 1 kalau belum login
        ]);

        // 5. Simpan ke tabel detail_pembelian
        DetailPembelian::create([
            'id_pembelian'   => $pembelian->id_pembelian,
            'id_detailobat'  => $detailObat->id_detailobat,
            'jumlah_beli'    => $request->jumlah_beli,
            'harga_beli'     => $request->harga_beli,
            'harga_jual'     => $request->harga_jual,
            'tgl_pembelian'  => $request->tgl_pembelian,
            'tgl_kadaluarsa' => $request->tgl_kadaluarsa,
        ]);

        return redirect()->route('pembelian.index')->with('success', 'Data pembelian berhasil ditambahkan.');
    }

    public function showDetail($id_detailbeli)
    {
        // Load relasi pembelian → admin, detailObat → obat
        $detail = DetailPembelian::with(['pembelian.admin', 'detailObat.obat'])->findOrFail($id_detailbeli);

        $pembelian = $detail->pembelian;
        $obat = $detail->detailObat->obat ?? null;

        return view('pembelian.detailpembelian', [
            'detail' => $detail,
            'nama_admin' => $pembelian->admin->nama_admin ?? 'Admin Tidak Diketahui',
            'nama_obat' => $obat->nama_obat ?? 'Tidak Diketahui',
            'jenis_obat' => $obat->jenis_obat ?? '-',             // ambil dari field 'jenis_obat' di tabel obat
            'keterangan_obat' => $obat->keterangan_obat ?? '-',   // ambil dari field 'keterangan_obat' di tabel obat
            'harga_jual' => $obat->harga_jual ?? 0,               // ambil dari field 'harga_jual' di tabel obat
            'tgl_kadaluarsa' => $detail->tgl_kadaluarsa ?? now(),
            'total' => $detail->jumlah_beli * $detail->harga_beli,
        ]);
    }

        public function edit($id)
        {
            $pembelian = Pembelian::with('detailPembelian')->findOrFail($id);
            return view('pembelian.editpembelian', compact('pembelian'));
        }

        public function update(Request $request, $id)
    {
        $pembelian = Pembelian::with('detailPembelian.detailObat.obat')->findOrFail($id);
        $detailPembelian = $pembelian->detailPembelian->first(); // Ambil detail pembelian pertama
        $detailObat = $detailPembelian->detailObat;
        $obat = $detailObat->obat;

        // Validasi input
        $request->validate([
            'nama_admin'        => 'required|string|max:255',
            'tgl_pembelian'     => 'required|date',
            'nama_obat'         => 'required|string|max:255',
            'jenis_obat'        => 'required|string|max:255',
            'keterangan_obat'   => 'required|string|max:255',
            'jumlah_beli'       => 'required|integer|min:1',
            'harga_beli'        => 'required|numeric|min:0',
            'harga_jual'        => 'required|numeric|min:0',
            'tgl_kadaluarsa'    => 'required|date',
        ]);

        // Hitung total
        $total = $request->jumlah_beli * $request->harga_beli;

        // Update Obat
        $obat->update([
            'nama_obat'        => $request->nama_obat,
            'jenis_obat'       => $request->jenis_obat,
            'keterangan_obat'  => $request->keterangan_obat,
            'harga_beli'       => $request->harga_beli,
            'harga_jual'       => $request->harga_jual,
            'stok_total'       => $request->jumlah_beli, // Update stok total obat
        ]);

        // Update Detail Obat
        $detailObat->update([
            'stok'             => $request->jumlah_beli, // Update stok
            'tgl_kadaluarsa'   => $request->tgl_kadaluarsa, // Update tanggal kadaluarsa
        ]);

        // Update Detail Pembelian
        $detailPembelian->update([
            'jumlah_beli'      => $request->jumlah_beli,  // Update jumlah beli
            'harga_beli'       => $request->harga_beli,   // Update harga beli
            'harga_jual'       => $request->harga_jual,   // Update harga jual
            'tgl_pembelian'    => $request->tgl_pembelian, // Update tanggal pembelian
            'tgl_kadaluarsa'   => $request->tgl_kadaluarsa, // Update tanggal kadaluarsa
        ]);

        // Update Pembelian
        $pembelian->update([
            'tgl_pembelian'    => $request->tgl_pembelian, // Update tanggal pembelian
            'total'            => $total,  // Update total pembelian
        ]);

        // Kirim total ke view
        return redirect()->route('pembelian.index')->with('success', 'Data pembelian berhasil diperbarui.');
    }



        public function destroy($id)
    {
        $pembelian = Pembelian::with('detailPembelian.detailObat.obat')->findOrFail($id);

        foreach ($pembelian->detailPembelian as $detailPembelian) {
            $detailObat = $detailPembelian->detailObat;
            $obat = $detailObat->obat ?? null;

            // Hapus detail pembelian terlebih dahulu
            $detailPembelian->delete();

            // Hapus detail obat setelah tidak ada yang terkait
            if ($detailObat) {
                $detailObat->delete();
            }

            // Baru hapus obat setelah detail_obat-nya terhapus
            if ($obat) {
                $obat->delete();
            }
        }

        // Terakhir hapus data pembelian
        $pembelian->delete();

        return redirect()->route('pembelian.index')->with('success', 'Data pembelian dan relasi berhasil dihapus.');
    }

    }
