<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonsentrasiJurusan extends Model
{
    use HasFactory;

    protected $table = 'konsentrasi_jurusan';

    protected $fillable = [
        'kurikulum_id',
        'kode_konsentrasi',
        'nama_konsentrasi',
        'deskripsi',
        'sub_konsentrasi',
        'status_verifikasi',
        'verifikasi_by',
        'alasan_verifikasi',
        'verifikasi_at',
    ];

    protected $casts = [
        'sub_konsentrasi' => 'array', // karena disimpan JSON
        'verifikasi_at' => 'datetime',
    ];

    // relasi ke kurikulum
    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }

    // relasi ke user yang memverifikasi
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verifikasi_by');
    }
}
