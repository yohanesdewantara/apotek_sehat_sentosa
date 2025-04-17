<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;

class Pembelian extends Model
{
    protected $table = 'pembelian'; // nama tabel
    protected $primaryKey = 'id_pembelian'; // primary key
    public $timestamps = false; // nonaktifkan timestamps kalau tidak pakai created_at, updated_at

    protected $fillable = ['id_admin', 'tgl_pembelian', 'total'];

    // Relasi ke admin
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin');
    }
    // Model Pembelian.php
    public function detail_pembelian()
    {
        // return $this->hasMany(DetailPembelian::class, 'id_pembelian');
        return $this->hasMany(DetailPembelian::class);
    }

    // Model DetailPembelian.php
    public function detail_obat()
    {
        return $this->belongsTo(DetailObat::class, 'id_detailobat');
    }

    // Model DetailObat.php
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
    public function detailPembelian()
{
    return $this->hasMany(\App\Models\DetailPembelian::class, 'id_pembelian');
}


}



