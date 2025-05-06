<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';
    public $timestamps = false;

    protected $fillable = [
        'id_admin',
        'tgl_penjualan',
        'total'
    ];


    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }


    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan', 'id_penjualan');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($penjualan) {
            $penjualan->detailPenjualan()->delete();
        });
    }

}
