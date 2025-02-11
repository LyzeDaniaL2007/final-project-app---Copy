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
        Schema::create('penyewaan', function (Blueprint $table) {
            $table->id('penyewaan_id');
            $table->unsignedBigInteger('penyewaan_pelanggan_id');
            $table->date('penyewaan_tglsewa')->nullable(); // Mengatur penyewaan_tglsewa menjadi nullable
            $table->date('penyewaan_tglkembali')->nullable(false);
            $table->enum('penyewaan_stspembayaran', ['Lunas', 'Belum Dibayar', 'DP'])->default('Belum Dibayar');
            $table->enum('penyewaan_sttskembali', ['Sudah Kembali', 'Belum Kembali'])->default('Belum Kembali');
            $table->integer('penyewaan_totalharga')->nullable(false);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('penyewaan_pelanggan_id')
                  ->references('pelanggan_id')
                  ->on('pelanggan')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyewaan');
    }
};
