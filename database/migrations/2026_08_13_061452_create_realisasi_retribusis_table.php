<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('realisasi_retribusis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upload_id')->constrained('upload_retribusis')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('kode_rekening');
            $table->string('nama_retribusi');
            $table->decimal('nilai', 15, 2);
            $table->string('periode', 20);
            $table->string('tahun', 4);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('realisasi_retribusis');
    }
};