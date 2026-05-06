<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@disdukcapil.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'nip' => '199001012020011001',
            'jabatan' => 'Administrator Sistem',
        ]);

        // Kepala Dinas
        User::create([
            'name' => 'Dr. Budi Santoso, M.Si',
            'email' => 'kepala@disdukcapil.go.id',
            'password' => Hash::make('password'),
            'role' => 'kepala_dinas',
            'nip' => '197505102000031001',
            'jabatan' => 'Kepala Dinas',
        ]);

        // Tenaga PPK
        $ppkData = [
            ['name' => 'Siti Aminah', 'nip' => '199203152019032001', 'jabatan' => 'Pelaksana Pencatatan Sipil'],
            ['name' => 'Ahmad Fauzi', 'nip' => '199105202018011002', 'jabatan' => 'Pelaksana Kependudukan'],
            ['name' => 'Dewi Lestari', 'nip' => '199308252020122001', 'jabatan' => 'Pelaksana Administrasi'],
            ['name' => 'Rudi Hartono', 'nip' => '199012102019031001', 'jabatan' => 'Pelaksana Verifikasi Data'],
            ['name' => 'Maya Putri', 'nip' => '199406182021022001', 'jabatan' => 'Pelaksana Pelayanan'],
        ];

        foreach ($ppkData as $ppk) {
            User::create([
                'name' => $ppk['name'],
                'email' => strtolower(str_replace(' ', '.', $ppk['name'])) . '@disdukcapil.go.id',
                'password' => Hash::make('password'),
                'role' => 'ppk',
                'nip' => $ppk['nip'],
                'jabatan' => $ppk['jabatan'],
            ]);
        }
    }
}