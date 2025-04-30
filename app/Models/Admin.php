<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Obat;


class Admin extends Authenticatable
{
    use HasFactory;


    protected $table = 'admin';


    protected $primaryKey = 'id_admin';


    public $incrementing = true;


    protected $keyType = 'int';


    public $timestamps = true;


    protected $fillable = [
        'nama_admin',
        'email',
        'password',
    ];

}
