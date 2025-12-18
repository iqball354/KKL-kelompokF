<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kurikulum;

class KurikulumSeeder extends Seeder
{
    public function run(): void
    {
        Kurikulum::query()->delete();

        $kurikulums = [
            [
                'id_kurikulum' => 'S2MM',
                'tahun' => 2023,
                'program_studi' => 'S2 Magister Manajemen',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'S1M',
                'tahun' => 2023,
                'program_studi' => 'S1 Manajemen',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'S1AK',
                'tahun' => 2023,
                'program_studi' => 'S1 Akuntansi',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'S1EP',
                'tahun' => 2023,
                'program_studi' => 'S1 Ekonomi Pembangunan',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'D3KB',
                'tahun' => 2023,
                'program_studi' => 'D3 Keuangan dan Perbankan',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'S1STI',
                'tahun' => 2023,
                'program_studi' => 'S1 Sistem dan Teknologi Informasi',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
        ];

        foreach ($kurikulums as $k) {
            Kurikulum::create($k);
        }
    }
}
