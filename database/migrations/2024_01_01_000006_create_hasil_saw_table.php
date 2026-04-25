<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_saw', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penduduk_id')->constrained('penduduk')->onDelete('cascade');
            $table->decimal('nilai_saw', 10, 6);                        // Skor Vi akhir
            $table->integer('ranking');                                  // Urutan peringkat
            $table->enum('rekomendasi', ['layak', 'tidak_layak']);
            $table->timestamp('dihitung_pada')->useCurrent();
            $table->foreignId('dihitung_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_saw');
    }
};
