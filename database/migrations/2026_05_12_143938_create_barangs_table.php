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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->string('nama', 50);
            $table->integer('stock')->default(0);
            $table->bigInteger('harga')->default(0);
            $table->unsignedBigInteger('satuan_id');
            $table->unsignedBigInteger('kategori_id');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('satuan_id')->references('id')->on('satuan_barangs');
            $table->foreign('kategori_id')->references('id')->on('kategori_barangs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
