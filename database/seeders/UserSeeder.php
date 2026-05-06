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
            'email' => 'admin@damkarkuningan.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'nip' => '199001012020011001',
            'jabatan' => 'Administrator Sistem',
        ]);

        // Pimpinan UPT Pemadam Kebakaran
        User::create([
            'name' => 'Drs. Suherman, S.H',
            'email' => 'pimpinan@damkarkuningan.go.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'nip' => '197505102000031001',
            'jabatan' => 'Kepala UPT Pemadam Kebakaran',
        ]);

        // Personil Pemadam Kebakaran
        $personilData = [
            ['name' => 'Dinas Suprapto, A.Md', 'nip' => '199203152019032001', 'jabatan' => 'Perwira Pencegahan'],
            ['name' => 'Slamet Wijaya, S.K.M', 'nip' => '199105202018011002', 'jabatan' => 'Perwira Pemadaman'],
            ['name' => 'Bambang Sutrisno', 'nip' => '199308252020122001', 'jabatan' => 'Bintara Pencegahan'],
            ['name' => 'Adi Nugroho', 'nip' => '199012102019031001', 'jabatan' => 'Bintara Pemadaman'],
            ['name' => 'Hendri Setiawan, A.Md.K3', 'nip' => '199406182021022001', 'jabatan' => 'Petugas Keselamatan Kerja'],
            ['name' => 'Mochammad Ridho', 'nip' => '199511232019031002', 'jabatan' => 'Bintara Pemadaman'],
            ['name' => 'Wahyu Pratama', 'nip' => '199607152020011003', 'jabatan' => 'Petugas Pengemudi'],
            ['name' => 'Yudi Hermawan', 'nip' => '199712202021031001', 'jabatan' => 'Bintara Pencegahan'],
        ];

        foreach ($personilData as $personil) {
            User::create([
                'name' => $personil['name'],
                'email' => strtolower(str_replace(' ', '.', $personil['name'])) . '@damkarkuningan.go.id',
                'password' => Hash::make('password'),
                'role' => 'personil',
                'nip' => $personil['nip'],
                'jabatan' => $personil['jabatan'],
            ]);
        }
    }
}