<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KeahlianDosenSeeder extends Seeder
{
    public function run(): void
    {
        $namaDosens = [
            'Dr. Ahmad Fauzi',
            'Prof. Siti Rahma',
            'Dr. Budi Santoso',
            'Dr. Lina Marlina',
            'Dr. Rudi Hartono',
            'Prof. Dian Lestari',
            'Dr. Hendra Saputra',
            'Dr. Maya Putri',
            'Dr. Fajar Nugroho',
            'Prof. Indah Wulandari',
            'Dr. Agus Prasetyo',
            'Dr. Nia Kurnia',
            'Dr. Arif Wijaya',
            'Prof. Siska Amelia',
            'Dr. Yudi Santoso',
            'Dr. Fitriani',
            'Dr. Joko Susanto',
            'Prof. Rina Dewi',
            'Dr. Taufik Hidayat',
            'Dr. Lilis Suryani',
            'Dr. Rio Pratama',
            'Prof. Wawan Setiawan',
            'Dr. Intan Permata',
            'Dr. Andi Saputra',
            'Dr. Rina Anggraini',
            'Prof. Hadi Susilo',
            'Dr. Sari Utami',
            'Dr. Bima Santoso',
            'Dr. Nurdin Ramadhan',
            'Prof. Yulia Pratiwi'
        ];

        for ($i = 0; $i < 30; $i++) {
            DB::table('keahlian_dosens')->insert([
                'nama_dosen' => $namaDosens[$i],
                'bidang_keahlian' => json_encode(['Keahlian ' . rand(1, 5), 'Keahlian ' . rand(6, 10)]),

                'dokumen_sertifikat' => json_encode(['sertifikat1.pdf', 'sertifikat2.pdf']),
                'deskripsi_sertifikat' => json_encode(['Sertifikat keahlian A', 'Sertifikat keahlian B']),
                'tahun_sertifikat' => json_encode([2019, 2021]),

                'dokumen_lainnya' => json_encode(['dokumen_lain1.pdf', 'dokumen_lain2.pdf']),
                'deskripsi_lainnya' => json_encode(['Dokumen pendukung A', 'Dokumen pendukung B']),
                'tahun_lainnya' => json_encode([2018, 2020]),

                'dokumen_pendidikan' => json_encode(['ijazah_s1.pdf', 'ijazah_s2.pdf']),
                'deskripsi_pendidikan' => json_encode(['S1 Teknik', 'S2 Manajemen']),
                'tahun_pendidikan' => json_encode([2010, 2014]),

                'link' => json_encode(['https://portfolio.com/' . Str::slug($namaDosens[$i]), 'https://github.com/' . Str::slug($namaDosens[$i])]),

                'status_kaprodi' => ['pending', 'disetujui', 'ditolak'][array_rand(['pending', 'disetujui', 'ditolak'])],
                'status_akademik' => ['pending', 'diterima'][array_rand(['pending', 'diterima'])],

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
