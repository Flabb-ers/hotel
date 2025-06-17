<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\TipeKamar;
use App\Models\Kamar;
use App\Models\Tamu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Layanan
        $layanan1 = Layanan::create([
            'nama_layanan' => 'WiFi Gratis',
            'harga' => 0
        ]);

        $layanan2 = Layanan::create([
            'nama_layanan' => 'Breakfast',
            'harga' => 50000
        ]);

        // Create Tipe Kamar
        $tipeStandard = TipeKamar::create([
            'nama_tipe' => 'Standard Room',
            'id_layanan' => $layanan1->id_layanan
        ]);

        $tipeDeluxe = TipeKamar::create([
            'nama_tipe' => 'Deluxe Room',
            'id_layanan' => $layanan2->id_layanan
        ]);

        // Create Kamar
        for ($i = 101; $i <= 110; $i++) {
            Kamar::create([
                'id_tipe_kamar' => $tipeStandard->id_tipe,
                'nomer_kamar' => (string)$i,
                'jumlah_bed' => 1,
                'harga' => 300000,
                'fasilitas' => 'AC, TV, WiFi',
                'status' => 'tersedia'
            ]);
        }

        for ($i = 201; $i <= 205; $i++) {
            Kamar::create([
                'id_tipe_kamar' => $tipeDeluxe->id_tipe,
                'nomer_kamar' => (string)$i,
                'jumlah_bed' => 2,
                'harga' => 500000,
                'fasilitas' => 'AC, TV, WiFi, Breakfast, Balcony',
                'status' => 'tersedia'
            ]);
        }

        // Create Sample Tamu
        Tamu::create([
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi@email.com',
            'password' => Hash::make('password'),
            'no_telp' => '08123456789',
            'alamat' => 'Jl. Merdeka No. 123, Jakarta',
            'tanda_pengenal' => '2025-05-30'
        ]);

        Tamu::create([
            'nama_lengkap' => 'Siti Aminah',
            'email' => 'siti@email.com',
            'password' => Hash::make('password'),
            'no_telp' => '08987654321',
            'alamat' => 'Jl. Sudirman No. 456, Bandung',
            'tanda_pengenal' => '2025-05-29'
        ]);
    }
}
