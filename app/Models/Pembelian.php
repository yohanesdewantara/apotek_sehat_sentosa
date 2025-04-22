<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian'; // Nama tabel
    protected $primaryKey = 'id_pembelian'; // Primary key
    public $timestamps = false; // Nonaktifkan timestamps

    protected $fillable = ['id_admin', 'tgl_pembelian', 'total'];

    // Relasi ke Admin
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin');
    }

    // Relasi ke Detail Pembelian
    public function detailPembelian()
{
    return $this->hasMany(DetailPembelian::class, 'id_pembelian', 'id_pembelian');
}

}
