<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeahlianDosen extends Model
{
    use HasFactory;

    protected $table = 'keahlian_dosens';

    protected $fillable = [
        'nama_dosen',
        'bidang_keahlian',
        'dokumen_sertifikat',
        'deskripsi_sertifikat',
        'tahun_sertifikat',
        'dokumen_lainnya',
        'deskripsi_lainnya',
        'tahun_lainnya',
        'dokumen_pendidikan',
        'deskripsi_pendidikan',
        'tahun_pendidikan',
        'link',
        'deskripsi_link',
        'status_akademik',
        'validasi_by',
        'alasan_validasi',
        'validasi_at',
    ];

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
        'deskripsi_link' => 'array',
        'validasi_at' => 'datetime',
    ];

    // Relasi ke User yang melakukan validasi
    public function validator()
    {
        return $this->belongsTo(User::class, 'validasi_by');
    }
}
