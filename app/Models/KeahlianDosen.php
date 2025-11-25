<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeahlianDosen extends Model
{
    use HasFactory;

    protected $table = 'keahlian_dosens';

    // Mass assignment
    protected $fillable = [
        'nama_dosen',          // penting supaya nama dosen bisa tersimpan
        'bidang_keahlian',

        // Sertifikat
        'dokumen_sertifikat',
        'deskripsi_sertifikat',
        'tahun_sertifikat',

        // Dokumen Lainnya
        'dokumen_lainnya',
        'deskripsi_lainnya',
        'tahun_lainnya',

        // Pendidikan
        'dokumen_pendidikan',
        'deskripsi_pendidikan',
        'tahun_pendidikan',

        // Link Dokumen / Portofolio
        'link',

        // Alur Kaprodi
        'status_kaprodi',

        // Alur Akademik
        'status_akademik',
    ];

    // Cast JSON fields ke array
    protected $casts = [
        'bidang_keahlian' => 'array',

        'dokumen_sertifikat' => 'array',
        'deskripsi_sertifikat' => 'array',
        'tahun_sertifikat' => 'array',

        'dokumen_lainnya' => 'array',
        'deskripsi_lainnya' => 'array',
        'tahun_lainnya' => 'array',

        'dokumen_pendidikan' => 'array',
        'deskripsi_pendidikan' => 'array',
        'tahun_pendidikan' => 'array',

        'link' => 'array',
    ];
}
