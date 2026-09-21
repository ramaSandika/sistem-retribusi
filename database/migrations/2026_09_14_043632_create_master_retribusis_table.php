<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_retribusis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rekening')->unique();
            $table->string('nama_retribusi');
            $table->string('opd_name')->nullable();
            $table->string('kategori')->default('Pajak & Retribusi Daerah');
            $table->decimal('target_anggaran', 18, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_retribusis');
    }
};
