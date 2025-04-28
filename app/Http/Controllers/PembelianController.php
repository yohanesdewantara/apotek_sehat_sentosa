<?php

    namespace App\Http\Controllers;

    use App\Models\DetailPembelian;
    use App\Models\Pembelian;
    use App\Models\DetailObat;
    use App\Models\Admin;
    use App\Models\Obat;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Http\Request;

    class PembelianController extends Controller
    {
        public function index(Request $request)
        {
            // Ambil parameter dari URL
            $date_from = $request->get('date_from'); // Tanggal Awal
            $date_to = $request->get('date_to'); // Tanggal Akhir
            $admin_name = $request->get('admin_name'); // Nama Admin

            $data = Pembelian::with('admin') // Mengambil data pembelian dengan relasi admin
                             ->when($admin_name, function($query, $admin_name) {
                                 return $query->whereHas('admin', function($q) use ($admin_name) {
                                     $q->where('nama_admin', 'like', '%' . $admin_name . '%');
                                 });
                             })
                             ->when($date_from && $date_to, function($query) use ($date_from, $date_to) {
                                 return $query->whereBetween('tgl_pembelian', [$date_from, $date_to]);
                             })
                             ->when($date_from && !$date_to, function($query) use ($date_from) {
                                 return $query->where('tgl_pembelian', '>=', $date_from);
                             })
                             ->when(!$date_from && $date_to, function($query) use ($date_to) {
                                 return $query->where('tgl_pembelian', '<=', $date_to);
                             })
                             ->get();

            return view('pembelian.pembelian', compact('data'));
        }


        public function create()
{
    $detailObats = DetailObat::with('obat')->get();
    $admins = Admin::all();
    $obats = Obat::all(); // ambil data semua obat

    return view('pembelian.createpembelian', compact('detailObats', 'admins', 'obats'));
}


public function store(Request $request)
{
    // Pastikan validasi berjalan dengan benar
    $request->validate([
        'id_admin'       => 'required|exists:admin,id_admin',
        'tgl_pembelian'  => 'required|date',
        'nama_obat.*'      => 'required|string|max:255',
        'jenis_obat.*'     => 'required|string|max:255',
        'keterangan_obat.*'=> 'required|string|max:255',
        'jumlah_beli.*'    => 'required|integer|min:1',
        'harga_beli.*'     => 'required|numeric|min:0',
        'harga_jual.*'     => 'required|numeric|min:0',
        'tgl_kadaluarsa.*' => 'required|date',
    ]);

    DB::beginTransaction();
    try {
        // Buat data pembelian
        $pembelian = Pembelian::create([
            'tgl_pembelian' => $request->tgl_pembelian,
            'total' => 0, // Total akan diupdate setelah menghitung semua total
            'id_admin' => $request->id_admin,
        ]);

        $total_harga = 0;

        // Loop semua inputan obat dan simpan
        foreach ($request->nama_obat as $index => $nama_obat) {
            // Simpan obat baru
            $obat = Obat::create([
                'id_rak'          => 1, // Cek apakah ini sudah sesuai
                'id_admin'        => $request->id_admin,
                'nama_obat'       => $nama_obat,
                'stok_total'      => $request->jumlah_beli[$index],
                'keterangan_obat' => $request->keterangan_obat[$index],
                'jenis_obat'      => $request->jenis_obat[$index],
                'harga_beli'      => $request->harga_beli[$index],
                'harga_jual'      => $request->harga_jual[$index],
            ]);

            // Simpan detail obat
            $detailObat = DetailObat::create([
                'id_obat'        => $obat->id_obat,
                'stok'           => $request->jumlah_beli[$index],
                'tgl_kadaluarsa' => $request->tgl_kadaluarsa[$index],
            ]);

            // Simpan detail pembelian
            DetailPembelian::create([
                'id_pembelian'   => $pembelian->id_pembelian,
                'id_detailobat'  => $detailObat->id_detailobat,
                'jumlah_beli'    => $request->jumlah_beli[$index],
                'harga_beli'     => $request->harga_beli[$index],
                'harga_jual'     => $request->harga_jual[$index],
                'tgl_pembelian'  => $request->tgl_pembelian,
                'tgl_kadaluarsa' => $request->tgl_kadaluarsa[$index],
            ]);

            // Hitung total harga
            $total_harga += $request->jumlah_beli[$index] * $request->harga_beli[$index];
        }

        // Update total harga di tabel pembelian
        $pembelian->update([
            'total' => $total_harga,
        ]);

        DB::commit();
        return redirect()->route('pembelian.index')->with('success', 'Data pembelian berhasil ditambahkan.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal menyimpan pembelian: ' . $e->getMessage());
    }
}



public function showDetail($id_detailbeli)
{
    $pembelian = Pembelian::with('admin', 'detailPembelian.detailObat.obat')->findOrFail($id_detailbeli);

    return view('pembelian.detailpembelian', [
        'pembelian' => $pembelian,
        'details' => $pembelian->detailPembelian,  // Kirim data detail pembelian
        'id_detailbeli' => $id_detailbeli,  // Kirim juga id_detailbeli ke view
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
        $detailPembelian = $pembelian->detailPembelian->first();
        $detailObat = $detailPembelian->detailObat;
        $obat = $detailObat->obat;


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


        $total = $request->jumlah_beli * $request->harga_beli;


        $obat->update([
            'nama_obat'        => $request->nama_obat,
            'jenis_obat'       => $request->jenis_obat,
            'keterangan_obat'  => $request->keterangan_obat,
            'harga_beli'       => $request->harga_beli,
            'harga_jual'       => $request->harga_jual,
            'stok_total'       => $request->jumlah_beli,
        ]);


        $detailObat->update([
            'stok'             => $request->jumlah_beli,
            'tgl_kadaluarsa'   => $request->tgl_kadaluarsa,
        ]);


        $detailPembelian->update([
            'jumlah_beli'      => $request->jumlah_beli,
            'harga_beli'       => $request->harga_beli,
            'harga_jual'       => $request->harga_jual,
            'tgl_pembelian'    => $request->tgl_pembelian,
            'tgl_kadaluarsa'   => $request->tgl_kadaluarsa,
        ]);


        $pembelian->update([
            'tgl_pembelian'    => $request->tgl_pembelian,
            'total'            => $total,
        ]);


        return redirect()->route('pembelian.index')->with('success', 'Data pembelian berhasil diperbarui.');
    }



        public function destroy($id)
    {
        $pembelian = Pembelian::with('detailPembelian.detailObat.obat')->findOrFail($id);

        foreach ($pembelian->detailPembelian as $detailPembelian) {
            $detailObat = $detailPembelian->detailObat;
            $obat = $detailObat->obat ?? null;


            $detailPembelian->delete();


            if ($detailObat) {
                $detailObat->delete();
            }


            if ($obat) {
                $obat->delete();
            }
        }


        $pembelian->delete();

        return redirect()->route('pembelian.index')->with('success', 'Data pembelian dan relasi berhasil dihapus.');
    }

    }
