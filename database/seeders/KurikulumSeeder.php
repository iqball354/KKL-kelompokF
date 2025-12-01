<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdentitasKurikulumTable extends Migration
{
    public function up()
    {
        Schema::create('identitas_kurikulum', function (Blueprint $table) {
            $table->id();
            $table->string('kode_identitas');      // contoh: RPL_2023
            $table->year('tahun');                 // tahun identitas kurikulum
            $table->string('program_studi');       // contoh: Rekayasa Perangkat Lunak
            $table->string('dokumen_kurikulum');   // path PDF
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('identitas_kurikulum');
    }
}
