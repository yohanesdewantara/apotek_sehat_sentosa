<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailStokOpname extends Model
{
    protected $table = 'detail_stokopname';
    protected $primaryKey = 'id_detailopname';
    public $timestamps = false;

    protected $fillable = [
        'id_detailopname',
        'id_opname',
        'id_detailobat',
        'stok_kadaluarsa',
        'tanggal_kadaluarsa',
        'keterangan'
    ];

    public function stokOpname()
    {
        return $this->belongsTo(StokOpname::class, 'id_opname', 'id_opname');
    }

    public function detailObat()
    {
        return $this->belongsTo(DetailObat::class, 'id_detailobat', 'id_detailobat');
    }
}
