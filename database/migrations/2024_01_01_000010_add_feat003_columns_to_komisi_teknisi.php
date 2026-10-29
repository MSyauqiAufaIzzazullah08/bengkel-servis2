<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop UNIQUE constraint on id_penugasan so NULL rows (home service, bonus)
        // can coexist. MySQL allows multiple NULLs in a UNIQUE index, but the
        // column is currently NOT NULL, so we must change it to nullable first.
        // We'll handle the unique constraint via application-level duplicate checks.

        // Make id_penugasan nullable if it is currently NOT NULL
        if (Schema::hasColumn('komisi_teknisi', 'id_penugasan')) {
            // Check if the column is nullable via information_schema
            $col = DB::selectOne(
                "SELECT IS_NULLABLE FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME   = 'komisi_teknisi'
                   AND COLUMN_NAME  = 'id_penugasan'"
            );
            if ($col && $col->IS_NULLABLE === 'NO') {
                // First drop the unique index
                try {
                    DB::statement('ALTER TABLE `komisi_teknisi` DROP INDEX `komisi_teknisi_id_penugasan_unique`');
                } catch (\Exception $e) {
                    // Index might have a different name; try the generic approach
                    try {
                        Schema::table('komisi_teknisi', function (Blueprint $table) {
                            $table->dropUnique(['id_penugasan']);
                        });
                    } catch (\Exception $e2) {
                        // Already dropped or different name — continue
                    }
                }
                // Now alter to nullable
                DB::statement('ALTER TABLE `komisi_teknisi` MODIFY `id_penugasan` INT UNSIGNED NULL');
            }
        }

        // Add id_teknisi — required to associate bonus rows with a teknisi
        if (!Schema::hasColumn('komisi_teknisi', 'id_teknisi')) {
            Schema::table('komisi_teknisi', function (Blueprint $table) {
                $table->unsignedInteger('id_teknisi')->nullable()->after('id_komisi');
            });
        }

        // Add id_booking
        if (!Schema::hasColumn('komisi_teknisi', 'id_booking')) {
            Schema::table('komisi_teknisi', function (Blueprint $table) {
                $table->unsignedBigInteger('id_booking')->nullable()->after('id_penugasan');
            });
        }

        // Add id_home_service
        if (!Schema::hasColumn('komisi_teknisi', 'id_home_service')) {
            Schema::table('komisi_teknisi', function (Blueprint $table) {
                $table->unsignedBigInteger('id_home_service')->nullable()->after('id_booking');
            });
        }

        // Add tipe_servis — 'bengkel' or 'home_service'
        if (!Schema::hasColumn('komisi_teknisi', 'tipe_servis')) {
            Schema::table('komisi_teknisi', function (Blueprint $table) {
                $table->enum('tipe_servis', ['bengkel', 'home_service'])->default('bengkel')->after('id_home_service');
            });
        }

        // Add is_bonus_target flag
        if (!Schema::hasColumn('komisi_teknisi', 'is_bonus_target')) {
            Schema::table('komisi_teknisi', function (Blueprint $table) {
                $table->tinyInteger('is_bonus_target')->default(0)->after('tipe_servis');
            });
        }

        // Change periode_bulan from CHAR(7) to DATE to store first-of-month dates
        // Only alter if still CHAR(7)
        $periodeCol = DB::selectOne(
            "SELECT DATA_TYPE, CHARACTER_MAXIMUM_LENGTH FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME   = 'komisi_teknisi'
               AND COLUMN_NAME  = 'periode_bulan'"
        );
        if ($periodeCol && $periodeCol->DATA_TYPE === 'char') {
            DB::statement('ALTER TABLE `komisi_teknisi` MODIFY `periode_bulan` DATE NULL');
        }
    }

    public function down(): void
    {
        // Reverse new columns
        foreach (['id_teknisi', 'id_booking', 'id_home_service', 'tipe_servis', 'is_bonus_target'] as $col) {
            if (Schema::hasColumn('komisi_teknisi', $col)) {
                Schema::table('komisi_teknisi', function (Blueprint $table) use ($col) {
                    $table->dropColumn($col);
                });
            }
        }
    }
};
