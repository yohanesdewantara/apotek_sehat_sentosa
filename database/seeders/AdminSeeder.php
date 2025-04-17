<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mengecek apakah admin dengan email ini sudah ada
        if (Admin::where('email', 'admin@apotek.com')->doesntExist()) {
            Admin::create([
                'nama_admin' => 'Admin Utama',
                'email' => 'admin@apotek.com',
                'password' => Hash::make('password123') // pastikan password terenkripsi
            ]);
    }
}
}
