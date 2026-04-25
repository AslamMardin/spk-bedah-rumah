<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penduduk', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('nama');
            $table->text('alamat');
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('no_hp', 15)->nullable();
            $table->integer('jumlah_anggota_keluarga')->default(1);
            // Upload foto kondisi rumah (PRD B: lampiran bukti)
            $table->string('foto_rumah')->nullable();
            $table->enum('status', ['pending', 'proses', 'diterima', 'ditolak'])->default('pending');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penduduk');
    }
};
