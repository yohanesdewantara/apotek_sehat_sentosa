<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RakObat extends Model
{
    use HasFactory;

    protected $table = 'rak_obat';
    protected $primaryKey = 'id_rak';
    public $timestamps = false;

    protected $fillable = [
        'nama_rak',
        'keterangan_rak'
    ];
    public function obat()
    {
        return $this->hasMany(Obat::class, 'id_rak', 'id_rak');  // Satu rak bisa memiliki banyak obat
    }

}
