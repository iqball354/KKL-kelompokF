<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kurikulum;

class KurikulumSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel agar seed bisa dijalankan ulang
        Kurikulum::query()->delete();

        $kurikulums = [
            // Fakultas Ekonomi dan Bisnis (FEB)
            [
                'id_kurikulum' => 'Kurikulum Merdeka FEB S2',
                'tahun' => 2023,
                'program_studi' => 'S2 Magister Manajemen',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'Kurikulum BEo FEB S1',
                'tahun' => 2023,
                'program_studi' => 'S1 Manajemen',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'Kurikulum MBKM FEB S1',
                'tahun' => 2023,
                'program_studi' => 'S1 Akuntansi',
                'dokumen_kurikulum' => null,
                'status' => 'nonaktif',
            ],
            [
                'id_kurikulum' => 'Kurikulum Pilihan FEB S1',
                'tahun' => 2023,
                'program_studi' => 'S1 Ekonomi Pembangunan',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'Kurikulum Pilihan FEB D3',
                'tahun' => 2023,
                'program_studi' => 'D3 Keuangan dan Perbankan',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],

            // Fakultas Sains, Teknologi dan Industri (FSTI)
            [
                'id_kurikulum' => 'Kurikulum Merdeka FSTI STI',
                'tahun' => 2023,
                'program_studi' => 'S1 Sistem dan Teknologi Informasi (STI)',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'Kurikulum BEo FSTI RPL',
                'tahun' => 2023,
                'program_studi' => 'S1 Rekayasa Perangkat Lunak (RPL)',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
        ];

        foreach ($kurikulums as $k) {
            Kurikulum::create($k);
        }
    }
}
