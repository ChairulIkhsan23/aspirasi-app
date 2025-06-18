<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('aspirasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('judul');
            $table->text('isi');
            $table->foreignId('topik_id')->nullable()->constrained()->onDelete('set null');
            $table->string('lampiran')->nullable();
            $table->boolean('is_anonim')->default(false);
            $table->boolean('flag_konten')->default(false); 
            $table->enum('status_moderasi', ['disetujui', 'perlu ditinjau'])->default('perlu ditinjau');
            $table->enum('status', ['Belum Diproses', 'Diproses', 'Ditolak', 'Selesai'])->default('Belum Diproses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aspirasis');
    }
};
