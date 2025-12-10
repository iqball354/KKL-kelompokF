<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konsentrasi_jurusan', function (Blueprint $table) {
            $table->id();

            // relasi ke kurikulum
            $table->unsignedBigInteger('kurikulum_id')->index();

            // info utama
            $table->string('kode_konsentrasi')->unique();
            $table->string('nama_konsentrasi');
            $table->text('deskripsi')->nullable();

            // sub-konsentrasi disimpan sebagai JSON
            $table->json('sub_konsentrasi')->nullable();

            // verifikasi akademik
            $table->enum('status_verifikasi', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->unsignedBigInteger('verifikasi_by')->nullable()->index();
            $table->text('alasan_verifikasi')->nullable();
            $table->timestamp('verifikasi_at')->nullable();

            $table->timestamps();

            // foreign key
            $table->foreign('kurikulum_id')
                ->references('id')
                ->on('kurikulums')
                ->onDelete('cascade');

            $table->foreign('verifikasi_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konsentrasi_jurusan');
    }
};
