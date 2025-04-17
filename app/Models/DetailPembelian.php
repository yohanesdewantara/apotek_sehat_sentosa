<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    protected $table = 'detail_pembelian';
    use HasFactory;
    public function obat()
    {
        return $this->belongsTo(DetailObat::class, 'id_detailobat');
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian');
    }

    public function detailObat()
{
    // return $this->belongsTo(\App\Models\DetailObat::class, 'id_detailobat');
    return $this->belongsTo(DetailObat::class, 'id_detailobat');
}

}
