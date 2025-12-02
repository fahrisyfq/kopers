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
        // Kolom penanda apakah ini transaksi Pre-Order
        $table->boolean('is_preorder')->default(false)->after('movement_type');
    });
}

public function down(): void
{
    Schema::table('stock_movements', function (Blueprint $table) {
        $table->dropColumn('is_preorder');
    });
}
};
