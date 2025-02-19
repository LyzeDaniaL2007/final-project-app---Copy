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
        Schema::create('alat', function (Blueprint $table) {
            $table->id('alat_id');
            $table->unsignedBigInteger('alat_kategori_id');
            $table->string('alat_nama', 255)->nullable(false);
            $table->text('alat_deskripsi')->nullable();
            $table->integer('alat_hargaperhari')->nullable(false);
            $table->integer('alat_stok')->nullable(false);
            $table->string('alat_gambar_file')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('alat_kategori_id')
                  ->references('kategori_id')
                  ->on('kategori')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
