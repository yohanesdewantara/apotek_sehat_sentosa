<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\RakObat;
use App\Models\DetailObat;
use App\Models\DetailStokOpname;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        $query = Obat::query();

        if ($request->filled('nama_obat')) {
            $query->where('nama_obat', 'like', '%' . $request->nama_obat . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $obats = $query->with('rakObat')->get();

        return view('obat.kelolaobat', compact('obats'));
    }

    public function kelolaobat(Request $request)
    {
        $query = Obat::query();

        if ($request->filled('nama_obat')) {
            $query->where('nama_obat', 'like', '%' . $request->nama_obat . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $obats = $query->get();

        return view('obat.kelolaobat', compact('obats'));
    }

    public function create()
    {
        $lastObat = Obat::orderBy('id_obat', 'desc')->first();

        if ($lastObat) {
            $nextId = (int) $lastObat->id_obat + 1;
        } else {
            $nextId = 1;
        }

        $nextDetailId = $nextId . '-1';

        // Ambil data rak
        $raks = RakObat::all();

        // opsi jenis obat
        $jenisObatOptions = [
            'Tablet' => 'Tablet',
            'Kapsul' => 'Kapsul',
            'Sirup' => 'Sirup',
            'Salep' => 'Salep',
            'Tetes' => 'Tetes',
            'Suntik' => 'Suntik',
            'Inhaler' => 'Inhaler',
            'Supositoria' => 'Supositoria',
            'Antibiotik' => 'Antibiotik',
            'Antiseptik' => 'Antiseptik',
            'Vitamin' => 'Vitamin',
            'Herbal' => 'Herbal',
            'Lainnya' => 'Lainnya'
        ];


        // Kirim ke view
        return view('obat.createobat', compact('nextId', 'nextDetailId', 'raks', 'jenisObatOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'jenis_obat' => 'required|string|max:100', // Changed from kategori to jenis_obat to match form
            'id_rak' => 'required|exists:rak_obat,id_rak',
            'stok' => 'required|array',
            'stok.*' => 'required|integer|min:0',
            'harga_beli' => 'required|array',
            'harga_beli.*' => 'required|numeric|min:0',


            'tgl_kadaluarsa' => 'required|array',
            'tgl_kadaluarsa.*' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $stokTotal = array_sum($request->stok);
            $avgHargaBeli = 0;
            if (count($request->harga_beli) > 0) {
                $avgHargaBeli = array_sum($request->harga_beli) / count($request->harga_beli);
            }

            $hargaJual = $avgHargaBeli * 1.2;

            $obat = Obat::create([
                'nama_obat' => $request->nama_obat,
                'jenis_obat' => $request->jenis_obat,
                'stok_total' => $stokTotal,
                'harga_beli' => $avgHargaBeli,
                'harga_jual' => $hargaJual,
            ]);

            for ($i = 0; $i < count($request->stok); $i++) {
                $obat->detailObat()->create([
                    'tgl_kadaluarsa' => $request->tgl_kadaluarsa[$i],
                    'stok' => $request->stok[$i],

                    'harga_beli' => $request->harga_beli[$i],
                ]);
            }

            DB::commit();
            return redirect()->route('obat.index')->with('success', 'Obat berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $obat = Obat::with([
            'rakObat',
            'detailObat' => function ($query) {
                $query->orderBy('tgl_kadaluarsa', 'asc');
            }
        ])->findOrFail($id);

        return view('obat.detailobat', compact('obat'));
    }

    public function edit($id)
    {
        $obat = Obat::with([
            'detailObat' => function ($query) {
                $query->with('detailPembelian');
            },
            'rakObat'
        ])->findOrFail($id);

        $raks = RakObat::all();

        return view('obat.editobat', compact('obat', 'raks'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'jenis_obat' => 'required|string|max:100',
            'rak_id' => 'required|exists:rak_obat,id_rak',
            'id_detailobat' => 'required|array',
            'stok' => 'required|array',
            'stok.*' => 'required|integer|min:0',
            'harga_beli' => 'required|array',
            'harga_beli.*' => 'required|numeric|min:0',
            'diskon' => 'required|array',
            'diskon.*' => 'required|numeric|min:0|max:100',
            'tgl_kadaluarsa' => 'required|array',
            'tgl_kadaluarsa.*' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $obat = Obat::findOrFail($id);

            // Update data utama obat
            $obat->update([
                'nama_obat' => $request->nama_obat,
                'jenis_obat' => $request->jenis_obat,
                'id_rak' => $request->rak_id,
            ]);

            $processedDetailIds = [];
            $totalStok = 0;
            $totalHargaBeli = 0;
            $countDetails = 0;

            // Proses detail obat
            for ($i = 0; $i < count($request->stok); $i++) {
                $detailId = $request->id_detailobat[$i];
                $hargaBeli = $request->harga_beli[$i] ?? 0;
                $stok = $request->stok[$i];

                $totalStok += $stok;
                $totalHargaBeli += $hargaBeli * $stok;
                $countDetails += $stok;

                if (strpos($detailId, 'new-') === 0) {
                    // Create new detail
                    $newDetail = $obat->detailObat()->create([
                        'id_obat' => $obat->id_obat,
                        'stok' => $stok,
                        'harga_beli' => $hargaBeli,
                        'disc' => $request->diskon[$i],
                        'tgl_kadaluarsa' => $request->tgl_kadaluarsa[$i],
                    ]);
                    $processedDetailIds[] = $newDetail->id_detailobat;
                } else {
                    $detail = $obat->detailObat()->where('id_detailobat', $detailId)->first();
                    if ($detail) {
                        $detail->update([
                            'stok' => $stok,
                            'harga_beli' => $hargaBeli,
                            'disc' => $request->diskon[$i],
                            'tgl_kadaluarsa' => $request->tgl_kadaluarsa[$i],
                        ]);
                        $processedDetailIds[] = $detailId;
                    }
                }
            }
            $obat->detailObat()->whereNotIn('id_detailobat', $processedDetailIds)->delete();
            $avgHargaBeli = $countDetails > 0 ? $totalHargaBeli / $countDetails : 0;
            $hargaJual = $avgHargaBeli * 1.2;

            $obat->update([
                'stok_total' => $totalStok,
                'harga_beli' => $avgHargaBeli,
                'harga_jual' => $hargaJual,
            ]);

            DB::commit();
            return redirect()->route('obat.index')->with('success', 'Data obat berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Gagal update data: ' . $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {
            $obat = Obat::findOrFail($id);
            $obat->delete();
            return redirect()->route('obat.index')->with('success', 'Data obat berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
    }

    public function showDiskon($id)
    {
        $obat = DetailObat::with('obat')->findOrFail($id);
        return view('obat.diskon', compact('obat'));
    }

    public function simpanDiskon(Request $request, $id)
    {
        $request->validate([
            'diskon' => 'required|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            $detailObat = DetailObat::with('obat')->findOrFail($id);

            $detailObat->disc = $request->diskon;
            $detailObat->save();

            DB::commit();

            return redirect()->route('obat.show', $detailObat->obat->id_obat)
                ->with('success', 'Diskon berhasil diterapkan untuk ID Detail Obat ' . $id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Gagal menyimpan diskon: ' . $e->getMessage()]);
        }
    }

    public function getObatInfo($id_obat)
    {
        $expiredStokOpnameIds = DetailStokOpname::join('detail_obat', 'detail_stokopname.id_detailobat', '=', 'detail_obat.id_detailobat')
            ->where('detail_obat.id_obat', $id_obat)
            ->pluck('detail_obat.id_detailobat')
            ->toArray();

        $obat = Obat::with([
            'detailObat' => function ($query) use ($expiredStokOpnameIds) {
                $query->where('stok', '>', 0)
                    ->whereNotIn('id_detailobat', $expiredStokOpnameIds)
                    ->orderBy('tgl_kadaluarsa', 'asc'); // FIFO order
            }
        ])->findOrFail($id_obat);

        $firstBatch = $obat->detailObat->first();

        $regularPrice = $obat->harga_jual;
        $discountPercent = 0;
        $discountedPrice = $regularPrice;

        if ($firstBatch && $firstBatch->disc > 0) {
            $discountPercent = $firstBatch->disc;
            $discountedPrice = $regularPrice * (1 - ($discountPercent / 100));
        }

        $availableStok = DetailObat::where('id_obat', $id_obat)
            ->where('stok', '>', 0)
            ->whereNotIn('id_detailobat', $expiredStokOpnameIds)
            ->sum('stok');

        return response()->json([
            'nama_obat' => $obat->nama_obat,
            'regular_price' => $regularPrice,
            'discounted_price' => $discountedPrice,
            'discount_percent' => $discountPercent,
            'has_discount' => ($discountPercent > 0),
            'stok_total' => $availableStok,
            'first_batch_id' => $firstBatch ? $firstBatch->id_detailobat : null,
            'is_available' => ($firstBatch !== null)
        ]);
    }
}
