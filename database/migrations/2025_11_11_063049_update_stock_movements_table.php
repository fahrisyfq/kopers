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
            if (!Schema::hasColumn('stock_movements', 'movement_type')) {
                $table->enum('movement_type', ['in', 'out', 'preorder'])->after('product_id');
            }

            if (!Schema::hasColumn('stock_movements', 'note')) {
                $table->text('note')->nullable()->after('quantity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            if (Schema::hasColumn('stock_movements', 'movement_type')) {
                $table->dropColumn('movement_type');
            }

            if (Schema::hasColumn('stock_movements', 'note')) {
                $table->dropColumn('note');
            }
        });
    }
};
