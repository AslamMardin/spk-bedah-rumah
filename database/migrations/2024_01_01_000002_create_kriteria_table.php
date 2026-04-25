<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();           // C1, C2, C3 ...
            $table->string('nama');                          // Nama kriteria
            $table->enum('tipe', ['benefit', 'cost']);       // benefit / cost
            $table->decimal('bobot', 5, 2);                  // 0.00 - 100.00 (total harus = 100)
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kriteria');
    }
};
