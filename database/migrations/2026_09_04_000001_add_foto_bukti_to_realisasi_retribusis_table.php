<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('upload_retribusis', 'foto_bukti')) {
            Schema::table('upload_retribusis', function (Blueprint $table) {
                $table->string('foto_bukti')->nullable()->after('original_filename');
            });
        }

        if (!Schema::hasColumn('realisasi_retribusis', 'foto_bukti')) {
            Schema::table('realisasi_retribusis', function (Blueprint $table) {
                $table->string('foto_bukti')->nullable()->after('nilai');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('upload_retribusis', 'foto_bukti')) {
            Schema::table('upload_retribusis', function (Blueprint $table) {
                $table->dropColumn('foto_bukti');
            });
        }

        if (Schema::hasColumn('realisasi_retribusis', 'foto_bukti')) {
            Schema::table('realisasi_retribusis', function (Blueprint $table) {
                $table->dropColumn('foto_bukti');
            });
        }
    }
};
