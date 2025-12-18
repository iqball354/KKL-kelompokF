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

        $kurikulum = Kurikulum::all()->keyBy('id_kurikulum');

        $data = [
            [
                'kurikulum_id' => $kurikulum['S2MM']->id,
                'kode_konsentrasi' => 'MS1,MS2,MS3',
                'nama_konsentrasi' => 'Manajemen Strategis',
                'sub_konsentrasi' => json_encode(['Strategi', 'Kepemimpinan', 'Perubahan']),
                'deskripsi' => 'Tujuan ini untuk penguatan strategi dan kepemimpinan organisasi. Berlaku sampai 2026.',
                'status_verifikasi' => 'disetujui',
            ],
            [
                'kurikulum_id' => $kurikulum['S1M']->id,
                'kode_konsentrasi' => 'MB1,MB2,MB3',
                'nama_konsentrasi' => 'Manajemen Bisnis',
                'sub_konsentrasi' => json_encode(['Pemasaran', 'Keuangan', 'SDM']),
                'deskripsi' => 'Tujuan ini untuk pengembangan kompetensi bisnis terpadu. Berlaku sampai 2026.',
                'status_verifikasi' => 'disetujui',
            ],
            [
                'kurikulum_id' => $kurikulum['S1AK']->id,
                'kode_konsentrasi' => 'AP1,AP2,AP3',
                'nama_konsentrasi' => 'Akuntansi dan Pelaporan',
                'sub_konsentrasi' => json_encode(['Pelaporan', 'Sistem', 'Analisis']),
                'deskripsi' => 'Tujuan ini untuk penguatan akuntansi dan pelaporan keuangan. Berlaku sampai 2026.',
                'status_verifikasi' => 'disetujui',
            ],
            [
                'kurikulum_id' => $kurikulum['S1EP']->id,
                'kode_konsentrasi' => 'ED1,ED2,ED3',
                'nama_konsentrasi' => 'Economic Development',
                'sub_konsentrasi' => json_encode(['Regional', 'Publik', 'Perencanaan']),
                'deskripsi' => 'Tujuan ini untuk analisis dan perencanaan pembangunan ekonomi. Berlaku sampai 2026.',
                'status_verifikasi' => 'disetujui',
            ],
            [
                'kurikulum_id' => $kurikulum['D3KB']->id,
                'kode_konsentrasi' => 'JKB1,JKB2,JKB3',
                'nama_konsentrasi' => 'Jasa Keuangan Bank',
                'sub_konsentrasi' => json_encode(['Operasional', 'Layanan', 'Kredit']),
                'deskripsi' => 'Tujuan ini untuk peningkatan kompetensi jasa keuangan dan perbankan. Berlaku sampai 2026.',
                'status_verifikasi' => 'disetujui',
            ],
            [
                'kurikulum_id' => $kurikulum['S1STI']->id,
                'kode_konsentrasi' => 'IOT1,IOT2,IOT3',
                'nama_konsentrasi' => 'Internet of Things',
                'sub_konsentrasi' => json_encode(['Embedded', 'Networking', 'Smart System']),
                'deskripsi' => 'Tujuan ini untuk pengembangan sistem IoT terintegrasi. Berlaku sampai 2026.',
                'status_verifikasi' => 'menunggu',
            ],
        ];

        foreach ($data as $item) {
            KonsentrasiJurusan::create($item);
        }
    }
}
