<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('realisasi_retribusis', function (Blueprint $table) {
            $table->decimal('anggaran', 20, 2)->default(0)->after('nama_retribusi');
            $table->decimal('persentase', 8, 2)->default(0)->after('nilai');
            $table->decimal('realisasi_lalu', 20, 2)->default(0)->after('persentase');
            $table->string('level_rekening', 20)->nullable()->after('realisasi_lalu'); // objek, rincian, sub_rincian
        });
    }

    public function down(): void {
        Schema::table('realisasi_retribusis', function (Blueprint $table) {
            $table->dropColumn(['anggaran', 'persentase', 'realisasi_lalu', 'level_rekening']);
        });
    }
};
