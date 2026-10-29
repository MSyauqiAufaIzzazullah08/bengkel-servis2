<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('penghargaan_teknisi')) {
            Schema::create('penghargaan_teknisi', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('teknisi_id');
                $table->unsignedInteger('cabang_id');
                $table->enum('periode_tipe', ['mingguan', 'bulanan', 'tahunan']);
                $table->date('periode_mulai');
                $table->date('periode_selesai');
                $table->integer('jumlah_servis')->default(0);
                $table->decimal('rata_rata_rating', 4, 2)->default(0.00);
                $table->decimal('skor_total', 8, 2)->default(0.00);
                $table->integer('peringkat')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();

                $table->foreign('teknisi_id')
                      ->references('id_teknisi')
                      ->on('Teknisi')
                      ->onDelete('cascade');

                $table->foreign('cabang_id')
                      ->references('id_cabang')
                      ->on('cabang')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('penghargaan_teknisi');
    }
};
