<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';
    protected $primaryKey = 'id_pembelian';
    public $timestamps = false;

    protected $fillable = ['id_admin', 'tgl_pembelian', 'total'];


    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin');

    }


    public function detailPembelian()
{
    return $this->hasMany(DetailPembelian::class, 'id_pembelian', 'id_pembelian');
}

}
