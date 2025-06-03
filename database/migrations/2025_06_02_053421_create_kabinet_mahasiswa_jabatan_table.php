<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kabinet_mahasiswa_jabatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('kabinet_id')->constrained('kabinets')->onDelete('cascade');
            $table->foreignId('jabatan_id')->constrained('jabatans')->onDelete('cascade');

            $table->unique(['mahasiswa_id', 'kabinet_id']); // Satu mahasiswa hanya boleh punya satu jabatan per kabinet
            $table->unique(['kabinet_id', 'jabatan_id']); // Satu jabatan hanya boleh diisi sekali per kabinet
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kabinet_mahasiswa_jabatan');
    }
};
