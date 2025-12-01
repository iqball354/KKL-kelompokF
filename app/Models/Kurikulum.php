<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kurikulum extends Model
{
    protected $table = 'kurikulums'; // pastikan sesuai nama tabel migration

    protected $fillable = [
        'kode_identitas',      
        'tahun',
        'program_studi',
        'dokumen_kurikulum',
        'status'
    ];
}
