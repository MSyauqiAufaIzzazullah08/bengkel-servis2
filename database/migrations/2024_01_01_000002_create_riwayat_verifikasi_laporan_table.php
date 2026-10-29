<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('riwayat_verifikasi_laporan')) {
            Schema::create('riwayat_verifikasi_laporan', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('laporan_id');
                $table->unsignedInteger('admin_id');
                $table->enum('aksi', ['disetujui', 'perlu_revisi']);
                $table->text('catatan')->nullable();
                $table->timestamp('created_at')->nullable();

                $table->foreign('laporan_id')
                      ->references('id')
                      ->on('laporan_servis')
                      ->onDelete('cascade');

                $table->foreign('admin_id')
                      ->references('id_user')
                      ->on('User')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_verifikasi_laporan');
    }
};
