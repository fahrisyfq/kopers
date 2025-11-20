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
        Schema::table('stock_movements', function (Blueprint $table) {
            // hanya tambahkan jika kolom belum ada
            if (!Schema::hasColumn('stock_movements', 'product_size_id')) {
                $table->foreignId('product_size_id')
                    ->nullable()
                    ->constrained('product_sizes')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            if (Schema::hasColumn('stock_movements', 'product_size_id')) {
                $table->dropConstrainedForeignId('product_size_id');
            }
        });
    }
};
