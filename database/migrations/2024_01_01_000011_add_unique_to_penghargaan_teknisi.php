<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add unique index (teknisi_id, periode_tipe, periode_mulai) if not already present
        $existingIndexes = DB::select("SHOW INDEX FROM penghargaan_teknisi WHERE Key_name = 'penghargaan_teknisi_unique'");

        if (empty($existingIndexes)) {
            // Check also for any existing unique on these 3 columns
            $hasUniq = collect(DB::select("SHOW INDEX FROM penghargaan_teknisi"))
                ->groupBy('Key_name')
                ->filter(fn($cols) => $cols->count() === 3
                    && $cols->contains('Column_name', 'teknisi_id')
                    && $cols->contains('Column_name', 'periode_tipe')
                    && $cols->contains('Column_name', 'periode_mulai')
                    && $cols->first()->Non_unique == 0
                )
                ->isNotEmpty();

            if (!$hasUniq) {
                Schema::table('penghargaan_teknisi', function (Blueprint $table) {
                    $table->unique(['teknisi_id', 'periode_tipe', 'periode_mulai'], 'penghargaan_teknisi_unique');
                });
            }
        }
    }

    public function down(): void
    {
        try {
            Schema::table('penghargaan_teknisi', function (Blueprint $table) {
                $table->dropUnique('penghargaan_teknisi_unique');
            });
        } catch (\Exception $e) {
            // Index may not exist
        }
    }
};
