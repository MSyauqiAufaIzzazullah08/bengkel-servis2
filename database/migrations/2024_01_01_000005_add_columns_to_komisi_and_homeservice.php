<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('komisi_teknisi', 'tipe')) {
            Schema::table('komisi_teknisi', function (Blueprint $table) {
                $table->enum('tipe', ['per_servis', 'bonus_target'])->default('per_servis');
            });
        }

        if (!Schema::hasColumn('komisi_teknisi', 'periode_bulan')) {
            Schema::table('komisi_teknisi', function (Blueprint $table) {
                $table->char('periode_bulan', 7)->nullable();
            });
        }

        if (!Schema::hasColumn('homeservice', 'id_laporan_servis')) {
            Schema::table('homeservice', function (Blueprint $table) {
                $table->unsignedInteger('id_laporan_servis')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('komisi_teknisi', 'tipe')) {
            Schema::table('komisi_teknisi', function (Blueprint $table) {
                $table->dropColumn('tipe');
            });
        }

        if (Schema::hasColumn('komisi_teknisi', 'periode_bulan')) {
            Schema::table('komisi_teknisi', function (Blueprint $table) {
                $table->dropColumn('periode_bulan');
            });
        }

        if (Schema::hasColumn('homeservice', 'id_laporan_servis')) {
            Schema::table('homeservice', function (Blueprint $table) {
                $table->dropColumn('id_laporan_servis');
            });
        }
    }
};
