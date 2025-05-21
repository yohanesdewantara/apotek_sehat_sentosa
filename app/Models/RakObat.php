<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RakObat extends Model
{
    use HasFactory;

    // Specify the table if it doesn't follow Laravel naming convention
    protected $table = 'rak_obat';

    // Set primary key if it's not 'id'
    protected $primaryKey = 'id_rak';

    // Disable timestamps if not needed
    public $timestamps = false;

    // Fillable fields for mass assignment
    protected $fillable = [
        'nama_rak',
        'keterangan_rak'
    ];

    /**
     * Get the obats for this rak.
     */
    public function obats()
    {
        return $this->hasMany(Obat::class, 'id_rak', 'id_rak');
    }
}
