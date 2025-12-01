<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kurikulums', function (Blueprint $table) {
            $table->id();
            $table->string('kode_identitas');       
            $table->string('tahun');                 
            $table->string('program_studi');         
            $table->string('dokumen_kurikulum')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();                   
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kurikulums');
    }
};
