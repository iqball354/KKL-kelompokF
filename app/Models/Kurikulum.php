<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kurikulum extends Model
{
    protected $table = 'kurikulums';

    protected $fillable = [
        'id_kurikulum',
        'tahun',
        'program_studi',
        'dokumen_kurikulum',
        'status',
    ];

    /**
     * Menghasilkan nama kurikulum otomatis.
     *
     * @return string
     */
    public function getKurikulumAttribute(): string
    {
        return $this->id_kurikulum . ' ' . $this->tahun;
    }
}
