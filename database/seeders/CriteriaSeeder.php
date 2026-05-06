<?php

namespace Database\Seeders;

use App\Models\Criteria;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    public function run(): void
{
    // Matikan foreign key sementara
    \Schema::disableForeignKeyConstraints();

    // Hapus data criteria
    Criteria::truncate();

    // Hidupkan kembali
    \Schema::enableForeignKeyConstraints();

    $criteria = [
        [
            'code' => 'C1',
            'name' => 'Kehadiran',
            'weight' => 0.50,
            'type' => 'benefit',
            'description' => 'Penilaian berdasarkan Absensi, Apel pagi, dan Lembur',
        ],
        [
            'code' => 'C2',
            'name' => 'Kinerja Pelayanan',
            'weight' => 0.30,
            'type' => 'benefit',
            'description' => 'Penilaian berdasarkan Pencetakan Akte, KTP, dan KK',
        ],
        [
            'code' => 'C3',
            'name' => 'Pelatihan',
            'weight' => 0.20,
            'type' => 'benefit',
            'description' => 'Jumlah pelatihan yang diikuti dalam periode penilaian',
        ],
    ];

    foreach ($criteria as $criterion) {
        Criteria::create($criterion);
    }
}
}