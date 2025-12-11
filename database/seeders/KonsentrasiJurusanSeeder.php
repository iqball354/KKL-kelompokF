<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KonsentrasiJurusan;
use App\Models\Kurikulum;

class KonsentrasiJurusanSeeder extends Seeder
{
    public function run(): void
    {
        KonsentrasiJurusan::query()->delete();

        // Ambil semua kurikulum yang ada, keyBy program_studi agar gampang dipanggil
        $allKurikulums = Kurikulum::all()->keyBy('program_studi');

        $konsentrasis = [
            // FEB
            [
                'kurikulum_id' => $allKurikulums['S2 Magister Manajemen']->id ?? null,
                'kode_konsentrasi' => 'MM2323-1',
                'nama_konsentrasi' => 'Manajemen Strategis',
                'sub_konsentrasi' => json_encode(['Leadership', 'Decision Making', 'HR', 'Finance', 'Marketing']),
                'deskripsi' => 'Tujuan: Mengembangkan kemampuan manajerial. Berlaku sampai 2025.',
                'status_verifikasi' => 'menunggu',
            ],
            [
                'kurikulum_id' => $allKurikulums['S1 Manajemen']->id ?? null,
                'kode_konsentrasi' => 'M12323-1',
                'nama_konsentrasi' => 'Manajemen Operasional',
                'sub_konsentrasi' => json_encode(['Produksi', 'Supply Chain', 'Quality', 'Logistics', 'Planning']),
                'deskripsi' => 'Tujuan: Fokus pada operasi bisnis efektif. Berlaku sampai 2025.',
                'status_verifikasi' => 'menunggu',
            ],
            [
                'kurikulum_id' => $allKurikulums['S1 Akuntansi']->id ?? null,
                'kode_konsentrasi' => 'AK2323-1',
                'nama_konsentrasi' => 'Akuntansi Keuangan',
                'sub_konsentrasi' => json_encode(['Audit', 'Laporan Keuangan', 'Pajak', 'Cost Accounting', 'Internal Control']),
                'deskripsi' => 'Tujuan: Pelaporan keuangan dan kepatuhan. Berlaku sampai 2025.',
                'status_verifikasi' => 'menunggu',
            ],
            [
                'kurikulum_id' => $allKurikulums['S1 Ekonomi Pembangunan']->id ?? null,
                'kode_konsentrasi' => 'EP2323-1',
                'nama_konsentrasi' => 'Ekonomi Regional',
                'sub_konsentrasi' => json_encode(['Pembangunan', 'Ekonomi Mikro', 'Ekonomi Makro', 'Analisis Data', 'Kebijakan Publik']),
                'deskripsi' => 'Tujuan: Analisis ekonomi pembangunan daerah. Berlaku sampai 2025.',
                'status_verifikasi' => 'menunggu',
            ],
            [
                'kurikulum_id' => $allKurikulums['D3 Keuangan dan Perbankan']->id ?? null,
                'kode_konsentrasi' => 'KB2323-1',
                'nama_konsentrasi' => 'Keuangan Perbankan',
                'sub_konsentrasi' => json_encode(['Manajemen Risiko', 'Kredit', 'Investasi', 'Analisis Keuangan', 'Produk Bank']),
                'deskripsi' => 'Tujuan: Kompetensi keuangan dan perbankan. Berlaku sampai 2025.',
                'status_verifikasi' => 'menunggu',
            ],

            // FSTI
            [
                'kurikulum_id' => $allKurikulums['S1 Sistem dan Teknologi Informasi']->id ?? null,
                'kode_konsentrasi' => 'STI2323-1',
                'nama_konsentrasi' => 'Web & IoT',
                'sub_konsentrasi' => json_encode(['Frontend', 'Backend', 'Database', 'IoT Devices', 'Cloud Integration']),
                'deskripsi' => 'Tujuan: Pengembangan aplikasi web & IoT. Berlaku sampai 2025.',
                'status_verifikasi' => 'menunggu',
            ],
            [
                'kurikulum_id' => $allKurikulums['S1 Rekayasa Perangkat Lunak']->id ?? null,
                'kode_konsentrasi' => 'RPL2323-1',
                'nama_konsentrasi' => 'Software Engineering',
                'sub_konsentrasi' => json_encode(['Design Patterns', 'Testing', 'DevOps', 'Agile', 'API Development']),
                'deskripsi' => 'Tujuan: Meningkatkan kompetensi rekayasa perangkat lunak. Berlaku sampai 2025.',
                'status_verifikasi' => 'menunggu',
            ],
        ];

        foreach ($konsentrasis as $k) {
            KonsentrasiJurusan::create($k);
        }
    }
}
