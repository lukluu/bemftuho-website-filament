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
        Schema::create('kelembagaans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // nama lembaga
            $table->string('slug')->unique();
            $table->string('jurusan')->nullable(); // deskripsi singkat
            $table->string('logo')->nullable(); // untuk upload logo
            $table->boolean('is_active')->default(true); // status aktif/nonaktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelembagaans');
    }
};
