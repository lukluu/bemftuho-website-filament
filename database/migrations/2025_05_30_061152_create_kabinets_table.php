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
        Schema::create('kabinets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kabinet');
            $table->string('slug')->unique();
            $table->string('visi');
            $table->string('misi');
            $table->string('periode');
            $table->string('tagline')->nullable();
            $table->string('logo')->nullable();
            $table->string('struktur_organisasi')->nullable();
            $table->string('color_primary')->nullable()->default('#800080');
            $table->string('color_secondary')->nullable()->default('#012169');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kabinets');
    }
};
