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
            $table->string('visi');
            $table->string('misi');
            $table->string('periode');
            $table->string('tagline')->nullable();
            $table->string('logo')->nullable();
            $table->string('color-primary')->nullable()->default('#002396b2');
            $table->string('color-secondary')->nullable()->default('#5c186d');
            $table->string('color-tertiary')->nullable()->default('#c3b6e7e3');
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
