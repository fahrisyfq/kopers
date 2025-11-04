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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Total harga semua item
            $table->decimal('total_price', 12, 2)->default(0);

            // Metode pembayaran (cash / kjp)
            $table->enum('payment_method', ['cash', 'kjp'])->default('cash');

            // Status pesanan
            $table->enum('status', ['Menunggu pembayaran', 'Diproses', 'Selesai'])
                  ->default('Menunggu pembayaran');

            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
