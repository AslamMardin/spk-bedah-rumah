<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Audit trail: mencatat setiap perubahan bobot kriteria oleh Admin
        // sesuai kebutuhan non-fungsional PRD bagian 6
        Schema::create('audit_trail_bobot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriteria_id')->constrained('kriteria')->onDelete('cascade');
            $table->decimal('bobot_lama', 5, 2);
            $table->decimal('bobot_baru', 5, 2);
            $table->foreignId('diubah_oleh')->constrained('users')->onDelete('cascade');
            $table->text('alasan')->nullable();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_trail_bobot');
    }
};
