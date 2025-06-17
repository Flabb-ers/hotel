<?php

namespace Database\Seeders;

use App\Models\Petugas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Petugas::create([
            'nama_lengkap' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@hotel.com',
            'password' => Hash::make('admin123'),
            'roles' => 'admin'
        ]);

        Petugas::create([
            'nama_lengkap' => 'Staff Hotel',
            'username' => 'staff',
            'email' => 'staff@hotel.com',
            'password' => Hash::make('staff123'),
            'roles' => 'staff'
        ]);
    }
}
