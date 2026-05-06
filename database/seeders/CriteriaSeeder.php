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
            'name' => 'Pangkat/Golongan',
            'weight' => 0.15,
            'type' => 'benefit',
            'description' => 'Tingkat pangkat atau golongan personil Pemadam Kebakaran',
        ],
        [
            'code' => 'C2',
            'name' => 'Tingkat Pendidikan',
            'weight' => 0.20,
            'type' => 'benefit',
            'description' => 'Jenjang pendidikan formal yang ditempuh',
        ],
        [
            'code' => 'C3',
            'name' => 'Umur',
            'weight' => 0.20,
            'type' => 'benefit',
            'description' => 'Usia personil pada saat penilaian',
        ],
        [
            'code' => 'C4',
            'name' => 'Masa Kerja',
            'weight' => 0.25,
            'type' => 'benefit',
            'description' => 'Lamanya masa kerja sebagai personil Pemadam Kebakaran',
        ],
        [
            'code' => 'C5',
            'name' => 'Penilaian Kinerja',
            'weight' => 0.20,
            'type' => 'benefit',
            'description' => 'Hasil evaluasi kinerja dalam periode penilaian',
        ],
    ];

    foreach ($criteria as $criterion) {
        Criteria::create($criterion);
    }
}
}