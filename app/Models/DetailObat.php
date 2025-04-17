<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailObat extends Model
{
    use HasFactory;
    protected $table = 'detail_obat';
    protected $primaryKey = 'id_detailobat';
    public $timestamps = false;

    public function obat()
    {
        // return $this->belongsTo(Obat::class, 'id_obat');
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}
