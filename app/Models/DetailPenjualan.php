<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Penjualan;
use App\Models\DetailObat;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan';
    protected $primaryKey = 'id_detailjual';
    public $timestamps = false;

    protected $fillable = [
        'id_penjualan',
        'id_detailobat',
        'tgl_penjualan',
        'jumlah_terjual',
        'harga_jual',
        'harga_beli'
    ];


    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan', 'id_penjualan');
    }


    public function detailObat()
    {
        return $this->belongsTo(DetailObat::class, 'id_detailobat', 'id_detailobat');
    }
}
