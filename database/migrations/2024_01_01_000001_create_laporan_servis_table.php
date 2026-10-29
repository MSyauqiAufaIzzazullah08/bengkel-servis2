<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('laporan_servis')) {
            Schema::create('laporan_servis', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('penugasan_id')->nullable();
                $table->unsignedInteger('booking_id')->nullable();
                $table->enum('sumber', ['bengkel', 'home_service'])->default('bengkel');
                $table->text('keluhan_awal')->nullable();
                $table->text('hasil_diagnosis')->nullable();
                $table->text('tindakan_perbaikan')->nullable();
                $table->json('komponen_digunakan')->nullable();
                $table->text('kondisi_motor_setelah')->nullable();
                $table->text('kendala_pekerjaan')->nullable();
                $table->text('catatan_rekomendasi')->nullable();
                $table->date('tanggal_selesai_pekerjaan')->nullable();
                $table->enum('status', ['draft', 'menunggu_verifikasi', 'perlu_revisi', 'disetujui'])->default('draft');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();

                $table->foreign('penugasan_id')
                      ->references('id_penugasan')
                      ->on('PenugasanTeknisi')
                      ->onDelete('set null');

                $table->foreign('booking_id')
                      ->references('id_booking')
                      ->on('bookingservis')
                      ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_servis');
    }
};
