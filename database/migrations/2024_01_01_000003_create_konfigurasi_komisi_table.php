<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('konfigurasi_komisi')) {
            Schema::create('konfigurasi_komisi', function (Blueprint $table) {
                $table->id();
                $table->integer('target_bulanan')->default(20);
                $table->decimal('komisi_per_servis', 15, 2)->default(10000.00);
                $table->decimal('bonus_target_bulanan', 15, 2)->default(150000.00);
                $table->date('berlaku_mulai');
                $table->unsignedInteger('created_by')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();

                $table->foreign('created_by')
                      ->references('id_user')
                      ->on('User')
                      ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('konfigurasi_komisi');
    }
};
