<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Obat;

class DetailObat extends Model
{
    use HasFactory;

    protected $table = 'detail_obat';
    protected $primaryKey = 'id_detailobat';
    public $timestamps = false;

    protected $fillable = ['id_obat', 'id_detailbeli', 'tgl_kadaluarsa', 'stok', 'disc', 'harga_beli'];

    // Relasi ke Obat
    public function obat()
{
    return $this->belongsTo(Obat::class, 'id_obat', 'id_obat');
}


}
