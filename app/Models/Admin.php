<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Tambahkan ini

class Admin extends Authenticatable // Ganti dari Model menjadi Authenticatable
{
    use HasFactory;

    // Nama tabelnya
    protected $table = 'admin';

    // Nama primary key
    protected $primaryKey = 'id_admin';

    // Jika primary key bukan increment id biasa (opsional, tergantung migrasi)
    public $incrementing = true;

    // Tipe primary key
    protected $keyType = 'int';

    // Untuk mengatur timestamp created_at dan updated_at
    public $timestamps = true;

    // Field yang boleh diisi (fillable)
    protected $fillable = [
        'nama_admin',
        'email',
        'password',
    ];

//     public function penjualan()
// {
//     return $this->belongsTo(Penjualan::class, 'id_admin', 'id_admin');
// }

// public function pembelian()
// {
//     return $this->belongsTo(Pembelian::class, 'id_admin', 'id_admin');
// }


}
