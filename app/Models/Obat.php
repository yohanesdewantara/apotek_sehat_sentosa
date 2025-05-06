<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obat';
    protected $primaryKey = 'id_obat';
    public $timestamps = false;



    protected $fillable = [
        'id_rak', 'nama_obat', 'stok_total', 'keterangan_obat', 'jenis_obat', 'harga_beli', 'harga_jual'
    ];



public function detailObat()
{
    return $this->hasMany(DetailObat::class, 'id_obat', 'id_obat');
}

public function rakObat()
{
    return $this->belongsTo(RakObat::class, 'id_rak', 'id_rak');  // Gunakan 'id_rak' sebagai foreign key
}


}
