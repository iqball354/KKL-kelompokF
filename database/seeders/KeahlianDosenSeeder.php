<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KeahlianDosenSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('keahlian_dosens')->insert([
            [
                'nama_dosen' => 'Dr. Andi Pratama',
                'bidang_keahlian' => json_encode(['Matematika', 'Statistika']),
                'dokumen_sertifikat' => json_encode(['sertifikat1.pdf', 'sertifikat2.pdf']),
                'deskripsi_sertifikat' => json_encode([
                    'Sertifikat Analisis Data tingkat lanjut.',
                    'Sertifikat Metode Statistik untuk penelitian.'
                ]),
                'tahun_sertifikat' => json_encode([2021, 2022]),

                'dokumen_lainnya' => json_encode(['makalah_penelitian1.pdf']),
                'deskripsi_lainnya' => json_encode(['Makalah penelitian statistika']),
                'tahun_lainnya' => json_encode([2023]),

                'dokumen_pendidikan' => json_encode(['ijazah_s1.pdf', 'ijazah_s2.pdf']),
                'deskripsi_pendidikan' => json_encode(['S1 Matematika, S2 Statistika']),
                'tahun_pendidikan' => json_encode([2015, 2018]),

                // 5 tipe link resmi (template)
                'link' => json_encode([
                    'https://www.linkedin.com/',
                    'https://scholar.google.com/',
                    'https://sinta.kemdikbud.go.id/',
                    'https://www.youtube.com/',
                    'https://example.com/cv/andi.pdf'
                ]),
                'deskripsi_link' => json_encode([
                    'LinkedIn resmi',
                    'Google Scholar resmi',
                    'SINTA resmi',
                    'YouTube Channel resmi',
                    'CV / Portofolio'
                ]),

                'status_akademik' => 'disetujui',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_dosen' => 'Prof. Rina Lestari',
                'bidang_keahlian' => json_encode(['Fisika', 'Kimia']),
                'dokumen_sertifikat' => json_encode(['sertifikat3.pdf']),
                'deskripsi_sertifikat' => json_encode([
                    'Sertifikat Fisika Lanjut bidang partikel.'
                ]),
                'tahun_sertifikat' => json_encode([2020]),

                'dokumen_lainnya' => json_encode(['jurnal_kimia1.pdf']),
                'deskripsi_lainnya' => json_encode(['Jurnal penelitian kimia organik']),
                'tahun_lainnya' => json_encode([2022]),

                'dokumen_pendidikan' => json_encode(['ijazah_s1.pdf', 'ijazah_s2.pdf']),
                'deskripsi_pendidikan' => json_encode(['S1 Fisika, S2 Kimia Analitik']),
                'tahun_pendidikan' => json_encode([2012, 2016]),

                'link' => json_encode([
                    'https://www.linkedin.com/',
                    'https://scholar.google.com/',
                    'https://sinta.kemdikbud.go.id/',
                    'https://www.youtube.com/',
                    'https://example.com/cv/rina.pdf'
                ]),
                'deskripsi_link' => json_encode([
                    'LinkedIn resmi',
                    'Google Scholar resmi',
                    'SINTA resmi',
                    'YouTube Channel resmi',
                    'CV / Portofolio'
                ]),

                'status_akademik' => 'menunggu',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);
    }
}
