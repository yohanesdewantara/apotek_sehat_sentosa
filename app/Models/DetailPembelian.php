<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelian';
    protected $primaryKey = 'id_detailbeli';
    public $timestamps = false;

    protected $fillable = [
        'id_pembelian',
        'id_detailobat',
        'tgl_pembelian',
        'jumlah_beli',
        'harga_beli',
        'tgl_kadaluarsa',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian');
    }

    public function detailObat()
    {
        return $this->belongsTo(DetailObat::class, 'id_detailobat', 'id_detailobat');
    }
}
