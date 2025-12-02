<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // Relasi ke User (Siapa yang review)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // [PERBAIKAN UTAMA] Relasi ke Product (Barang apa yang direview)
            $table->foreignId('product_id')->constrained()->cascadeOnDelete(); 
            
            $table->integer('rating');
            $table->text('body')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};