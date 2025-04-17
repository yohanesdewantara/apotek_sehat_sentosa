<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin'; // <- penting!
    protected $primaryKey = 'id_admin'; // <- sesuai field primary key kamu
    public $timestamps = true; // kalau pakai created_at & updated_at
    protected $fillable = ['nama_admin', 'email', 'password'];
}
