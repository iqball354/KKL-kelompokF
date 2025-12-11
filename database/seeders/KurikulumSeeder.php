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
                'id_kurikulum' => 'Kurikulum Merdeka FEB',
                'tahun' => 2023,
                'program_studi' => 'S2 Magister Manajemen',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'Kurikulum Tinjauan FEB',
                'tahun' => 2023,
                'program_studi' => 'S2 Magister Manajemen',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'Kurikulum BEo FEB',
                'tahun' => 2023,
                'program_studi' => 'S1 Manajemen',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'Kurikulum Tinjauan BEo FEB',
                'tahun' => 2023,
                'program_studi' => 'S1 Manajemen',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'Kurikulum MBKM FEB',
                'tahun' => 2023,
                'program_studi' => 'S1 Akuntansi',
                'dokumen_kurikulum' => null,
                'status' => 'nonaktif',
            ],
            [
                'id_kurikulum' => 'Kurikulum Tinjauan MBKM FEB',
                'tahun' => 2023,
                'program_studi' => 'S1 Akuntansi',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'Kurikulum Pilihan FEB',
                'tahun' => 2023,
                'program_studi' => 'S1 Ekonomi Pembangunan',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'Kurikulum Tinjauan Pilihan FEB',
                'tahun' => 2023,
                'program_studi' => 'S1 Ekonomi Pembangunan',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'Kurikulum Pilihan FEB',
                'tahun' => 2023,
                'program_studi' => 'D3 Keuangan dan Perbankan',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'Kurikulum Tinjauan Pilihan FEB',
                'tahun' => 2023,
                'program_studi' => 'D3 Keuangan dan Perbankan',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],

            // Fakultas Sains, Teknologi dan Industri (FSTI)
            [
                'id_kurikulum' => 'Kurikulum Merdeka',
                'tahun' => 2023,
                'program_studi' => 'S1 Sistem dan Teknologi Informasi',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'Kurikulum Tinjauan Merdeka',
                'tahun' => 2023,
                'program_studi' => 'S1 Sistem dan Teknologi Informasi',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'Kurikulum BEo',
                'tahun' => 2023,
                'program_studi' => 'S1 Rekayasa Perangkat Lunak',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
            [
                'id_kurikulum' => 'Kurikulum Tinjauan BEo',
                'tahun' => 2023,
                'program_studi' => 'S1 Rekayasa Perangkat Lunak',
                'dokumen_kurikulum' => null,
                'status' => 'aktif',
            ],
        ];

        foreach ($kurikulums as $k) {
            Kurikulum::create($k);
        }
    }
}
