<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
    protected $table = 'stok_opname';
    protected $primaryKey = 'id_opname';
    public $timestamps = false;

    protected $fillable = [
        'id_opname',
        'id_detailobat',
        'id_admin',
        'tanggal'
    ];

    public function detailStokOpname()
    {
        return $this->hasMany(DetailStokOpname::class, 'id_opname', 'id_opname');
    }

    public function detailObat()
    {
        return $this->belongsTo(DetailObat::class, 'id_detailobat', 'id_detailobat');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }
}
