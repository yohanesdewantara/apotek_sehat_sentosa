<?php

namespace App\Http\Controllers;

use App\Models\StokOpname;
use App\Models\DetailObat;
use App\Models\DetailStokOpname;
use App\Models\Admin;
use App\Models\Obat;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StokopnameController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data stok opname dengan relasi ke detailObat dan admin
        $stokopnames = StokOpname::with(['detailObat.obat', 'admin', 'detailStokOpname']);

        // Jika ada filter pencarian berdasarkan id_opname
        if ($request->has('id_opname') && $request->id_opname != '') {
            $stokopnames = $stokopnames->where('id_opname', 'like', '%' . $request->id_opname . '%');
        }

        // Ambil data stok opname yang sudah di-filter
        $stokopnames = $stokopnames->get();

        return view('stokopname.stokopname', compact('stokopnames'));
    }

    public function create()
    {
        // Mengambil data detail obat yang masih memiliki stok > 0
        $detailObats = DetailObat::with('obat')
            ->where('stok', '>', 0)
            ->orderBy('tgl_kadaluarsa', 'asc')
            ->get();
        $obats = Obat::whereHas('detailObat', function($query) {
            $query->where('stok', '>', 0);
        })->get();

        // Mengambil ID opname terakhir dan menambahkan 1 pada ID tersebut
        $lastStokOpname = StokOpname::orderByDesc('id_opname')->first();
        $newtIdOpname = $lastStokOpname ? $lastStokOpname->id_opname + 1 : 1;

        // Generate ID detail opname otomatis (misalnya pakai auto-increment terakhir + 1)
        $lastDetailOpname = DetailStokOpname::orderByDesc('id_detailopname')->first();
        $newIdDetailOpname = ($lastDetailOpname ? $lastDetailOpname->id_detailopname + 1 : 1);

        return view('stokopname.createstok', compact('detailObats', 'obats', 'newtIdOpname', 'newIdDetailOpname'));
    }

    public function store(Request $request)
    {
        // Validasi input dengan aturan yang lebih ketat
        $validated = $request->validate([
            'id_opname' => 'required|integer',
            'id_detailopname' => 'required|integer',
            'id_detailobat' => 'required|exists:detail_obat,id_detailobat',
            'tanggal' => 'required|date',
            'tanggal_kadaluarsa' => 'required|date',
            'stok_fisik' => 'required|integer|min:0', // Tidak boleh minus
            'jenis' => 'required|in:penambahan,pengurangan,normal',
            'qty' => 'required|integer|min:0', // Qty tidak boleh minus
            'stok_akhir' => 'required|integer|min:0', // Stok akhir tidak boleh minus
            'keterangan' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Ambil admin ID
            $adminId = 1;
            if (auth()->check()) {
                $adminId = auth()->user()->id_admin;
            }

            // Ambil detail obat untuk validasi
            $detailObat = DetailObat::findOrFail($validated['id_detailobat']);
            $obat = Obat::findOrFail($detailObat->id_obat);

            $stokSystemLama = $detailObat->stok;

            // Validasi untuk pengurangan - qty tidak boleh melebihi stok sistem
            if ($validated['jenis'] === 'pengurangan') {
                if ($validated['qty'] > $stokSystemLama) {
                    return redirect()->back()
                        ->with('error', 'Qty pengurangan (' . $validated['qty'] . ') tidak boleh melebihi stok sistem (' . $stokSystemLama . ')!')
                        ->withInput();
                }
            }

            // Hitung stok akhir berdasarkan jenis penyesuaian
            $stokAkhir = $stokSystemLama;
            if ($validated['jenis'] === 'penambahan') {
                $stokAkhir = $stokSystemLama + $validated['qty'];
            } elseif ($validated['jenis'] === 'pengurangan') {
                $stokAkhir = $stokSystemLama - $validated['qty'];
            }

            // Pastikan stok akhir tidak minus
            if ($stokAkhir < 0) {
                return redirect()->back()
                    ->with('error', 'Stok akhir tidak boleh minus! Stok sistem: ' . $stokSystemLama . ', Pengurangan: ' . $validated['qty'])
                    ->withInput();
            }

            // Validasi konsistensi dengan input stok_akhir
            if ($stokAkhir != $validated['stok_akhir']) {
                return redirect()->back()
                    ->with('error', 'Perhitungan stok akhir tidak konsisten. Harap periksa kembali input Anda.')
                    ->withInput();
            }

            // Buat record StokOpname
            $stokOpname = new StokOpname();
            $stokOpname->id_opname = $validated['id_opname'];
            $stokOpname->id_detailobat = $validated['id_detailobat'];
            $stokOpname->id_admin = $adminId;
            $stokOpname->tanggal = $validated['tanggal'];
            $stokOpname->save();

            // Buat record DetailStokOpname dengan field yang sesuai ERD
            $detailStokOpname = new DetailStokOpname();
            $detailStokOpname->id_detailopname = $validated['id_detailopname'];
            $detailStokOpname->id_opname = $stokOpname->id_opname;
            $detailStokOpname->id_detailobat = $validated['id_detailobat'];
            $detailStokOpname->stok_kadaluarsa = $validated['stok_fisik']; // Menggunakan field yang ada di ERD
            $detailStokOpname->tanggal_kadaluarsa = $validated['tanggal_kadaluarsa'];
            $detailStokOpname->keterangan = $validated['keterangan'];
            $detailStokOpname->save();

            // Update stok di DetailObat
            $detailObat->stok = $stokAkhir;
            $detailObat->save();

            // Update stok total di Obat
            $selisihStok = $stokAkhir - $stokSystemLama;
            $obat->stok_total = $obat->stok_total + $selisihStok;
            $obat->save();

            DB::commit();

            // Pesan sukses yang informatif
            $pesanJenis = '';
            if ($validated['jenis'] === 'penambahan') {
                $pesanJenis = ' Stok ditambah ' . $validated['qty'] . ' unit.';
            } elseif ($validated['jenis'] === 'pengurangan') {
                $pesanJenis = ' Stok dikurangi ' . $validated['qty'] . ' unit.';
            } else {
                $pesanJenis = ' Tidak ada perubahan stok.';
            }

            return redirect()->route('stokopname.index')
                ->with('success', 'Stok opname berhasil ditambahkan!' . $pesanJenis . ' Stok akhir: ' . $stokAkhir);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan stok opname: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $stokopname = StokOpname::with([
            'detailStokOpname.detailObat.obat',
            'detailObat.obat',
            'admin'
        ])->findOrFail($id);

        return view('stokopname.detailstok', compact('stokopname'));
    }

    public function edit($id)
    {
        // Ambil data StokOpname dengan eager loading detailStokOpname
        $stokopname = StokOpname::with(['detailStokOpname', 'detailObat.obat', 'admin'])->findOrFail($id);
        $detailStokOpname = DetailStokOpname::where('id_opname', $id)->first();

        if (!$detailStokOpname) {
            return redirect()->route('stokopname.index')->with('error', 'Detail stok opname tidak ditemukan!');
        }

        // Ambil data untuk dropdown
        $detailObats = DetailObat::with('obat')
            ->where('stok', '>', 0)
            ->orderBy('tgl_kadaluarsa', 'asc')
            ->get();
        $obats = Obat::whereHas('detailObat', function($query) {
            $query->where('stok', '>', 0);
        })->get();

        return view('stokopname.editstok', compact('stokopname', 'detailStokOpname', 'detailObats', 'obats'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input dengan aturan yang lebih ketat
        $validated = $request->validate([
            'id_detailobat' => 'required|exists:detail_obat,id_detailobat',
            'tanggal_kadaluarsa' => 'required|date',
            'stok_fisik' => 'required|integer|min:0', // Tidak boleh minus
            'jenis' => 'required|in:penambahan,pengurangan,normal',
            'qty' => 'required|integer|min:0', // Qty tidak boleh minus
            'stok_akhir' => 'required|integer|min:0', // Stok akhir tidak boleh minus
            'keterangan' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Ambil data lama untuk rollback stok
            $stokOpname = StokOpname::findOrFail($id);
            $detailStokOpname = DetailStokOpname::where('id_opname', $id)->first();

            if (!$detailStokOpname) {
                return redirect()->back()->with('error', 'Detail stok opname tidak ditemukan!');
            }

            // Rollback stok lama ke kondisi sebelum opname
            $detailObatLama = DetailObat::findOrFail($stokOpname->id_detailobat);
            $obatLama = Obat::findOrFail($detailObatLama->id_obat);

            // Kembalikan ke stok sistem original (sebelum opname)
            $stokSystemOriginal = $detailObatLama->stok; // Stok saat ini

            // Ambil detail obat baru
            $detailObatBaru = DetailObat::findOrFail($validated['id_detailobat']);
            $obatBaru = Obat::findOrFail($detailObatBaru->id_obat);

            $stokSystemBaru = $detailObatBaru->stok;

            // Validasi untuk pengurangan - qty tidak boleh melebihi stok sistem
            if ($validated['jenis'] === 'pengurangan') {
                if ($validated['qty'] > $stokSystemBaru) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'Qty pengurangan (' . $validated['qty'] . ') tidak boleh melebihi stok sistem (' . $stokSystemBaru . ')!')
                        ->withInput();
                }
            }

            // Hitung stok akhir berdasarkan jenis penyesuaian
            $stokAkhir = $stokSystemBaru;
            if ($validated['jenis'] === 'penambahan') {
                $stokAkhir = $stokSystemBaru + $validated['qty'];
            } elseif ($validated['jenis'] === 'pengurangan') {
                $stokAkhir = $stokSystemBaru - $validated['qty'];
            }

            // Pastikan stok akhir tidak minus
            if ($stokAkhir < 0) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Stok akhir tidak boleh minus! Stok sistem: ' . $stokSystemBaru . ', Pengurangan: ' . $validated['qty'])
                    ->withInput();
            }

            // Validasi konsistensi dengan input stok_akhir
            if ($stokAkhir != $validated['stok_akhir']) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Perhitungan stok akhir tidak konsisten. Harap periksa kembali input Anda.')
                    ->withInput();
            }

            // Update StokOpname
            $stokOpname->id_detailobat = $validated['id_detailobat'];
            $stokOpname->save();

            // Update DetailStokOpname dengan field yang sesuai ERD
            $detailStokOpname->id_detailobat = $validated['id_detailobat'];
            $detailStokOpname->stok_kadaluarsa = $validated['stok_fisik']; // Menggunakan field yang ada di ERD
            $detailStokOpname->tanggal_kadaluarsa = $validated['tanggal_kadaluarsa'];
            $detailStokOpname->keterangan = $validated['keterangan'];
            $detailStokOpname->save();

            // Terapkan penyesuaian stok baru
            $detailObatBaru->stok = $stokAkhir;
            $detailObatBaru->save();

            // Update stok total di Obat
            $selisihStokBaru = $stokAkhir - $stokSystemBaru;
            $obatBaru->stok_total = $obatBaru->stok_total + $selisihStokBaru;
            $obatBaru->save();

            DB::commit();

            // Pesan sukses yang informatif
            $pesanJenis = '';
            if ($validated['jenis'] === 'penambahan') {
                $pesanJenis = ' Stok ditambah ' . $validated['qty'] . ' unit.';
            } elseif ($validated['jenis'] === 'pengurangan') {
                $pesanJenis = ' Stok dikurangi ' . $validated['qty'] . ' unit.';
            } else {
                $pesanJenis = ' Tidak ada perubahan stok.';
            }

            return redirect()->route('stokopname.index')
                ->with('success', 'Data stok opname berhasil diperbarui!' . $pesanJenis . ' Stok akhir: ' . $stokAkhir);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui stok opname: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Ambil data untuk rollback stok
            $stokOpname = StokOpname::findOrFail($id);
            $detailStokOpname = DetailStokOpname::where('id_opname', $id)->first();

            if ($detailStokOpname) {
                // Rollback stok ke kondisi sebelum opname
                $detailObat = DetailObat::find($stokOpname->id_detailobat);
                if ($detailObat) {
                    $obat = Obat::find($detailObat->id_obat);

                    // Restore ke stok original sebelum opname
                    // Ini membutuhkan data tambahan untuk mengetahui stok sebelumnya
                    // Untuk sementara, kita akan menggunakan logika sederhana

                    // Hapus dulu data opname
                    DetailStokOpname::where('id_opname', $id)->delete();
                    $stokOpname->delete();
                }
            } else {
                // Jika tidak ada detail, hapus saja
                $stokOpname->delete();
            }

            DB::commit();

            return redirect()->route('stokopname.index')->with('success', 'Stok opname berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus stok opname: ' . $e->getMessage());
        }
    }

    /**
     * Fungsi helper untuk validasi qty tidak minus
     */
    private function validateQtyNotMinus($qty, $jenis, $stokSystem)
    {
        // Qty tidak boleh minus
        if ($qty < 0) {
            return false;
        }

        // Untuk pengurangan, qty tidak boleh melebihi stok sistem
        if ($jenis === 'pengurangan' && $qty > $stokSystem) {
            return false;
        }

        return true;
    }

    /**
     * Fungsi helper untuk menghitung stok akhir
     */
    private function hitungStokAkhir($stokSystem, $jenis, $qty)
    {
        switch ($jenis) {
            case 'penambahan':
                return $stokSystem + $qty;
            case 'pengurangan':
                return max(0, $stokSystem - $qty); // Pastikan tidak minus
            case 'normal':
            default:
                return $stokSystem;
        }
    }
}
