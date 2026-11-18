<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Menambahkan data user
        DB::table('users')->insert([
            'nama' => 'Manda Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123'), // Gantilah password sesuai kebutuhan
            'remember_token' => null,
            'role' => 'admin', // Role admin
            'no_hp' => '081234567890', // Nomor HP
            'user_image' => null, // Foto profil bisa null
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Menambahkan data user biasa
        DB::table('users')->insert([
            'nama' => 'Yosep User',
            'email' => 'user@user.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123'), // Gantilah password sesuai kebutuhan
            'remember_token' => null,
            'role' => 'user',
            'no_hp' => null,
            'user_image' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
