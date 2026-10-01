<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('upload_retribusis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('tahun', 4);
            $table->string('periode', 20);
            $table->string('unit_opd');
            $table->text('keterangan')->nullable();
            $table->string('file_path');
            $table->enum('status', ['Processing', 'Success', 'Failed'])->default('Processing');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('upload_retribusis');
    }
};