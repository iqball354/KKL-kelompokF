<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keahlian_dosens', function (Blueprint $table) {
            $table->id();

            // Nama Dosen
            $table->string('nama_dosen')->nullable();

            // Bidang keahlian
            $table->json('bidang_keahlian')->nullable();

            // Dokumen Sertifikat
            $table->json('dokumen_sertifikat')->nullable();
            $table->json('deskripsi_sertifikat')->nullable();
            $table->json('tahun_sertifikat')->nullable();

            // Dokumen Lainnya
            $table->json('dokumen_lainnya')->nullable();
            $table->json('deskripsi_lainnya')->nullable();
            $table->json('tahun_lainnya')->nullable();

            // Dokumen Pendidikan
            $table->json('dokumen_pendidikan')->nullable();
            $table->json('deskripsi_pendidikan')->nullable();
            $table->json('tahun_pendidikan')->nullable();

            // Link Dokumen / Portofolio
            $table->json('link')->nullable();

            // Status akademik / validasi
            $table->enum('status_akademik', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->unsignedBigInteger('validasi_by')->nullable()->index();
            $table->text('alasan_validasi')->nullable();
            $table->timestamp('validasi_at')->nullable();

            $table->timestamps();

            // Foreign key
            $table->foreign('validasi_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keahlian_dosens');
    }
};
