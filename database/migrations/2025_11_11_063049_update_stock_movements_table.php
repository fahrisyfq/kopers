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
        // UBAH DARI Schema::table JADI Schema::create
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id(); // Wajib ada ID

            // Kita tambahkan kolom standar yang biasanya dibutuhkan
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                // KOLOM BARU: Product Size (Kita pakai unsignedBigInteger biar aman)
            $table->unsignedBigInteger('product_size_id')->nullable();
            $table->integer('quantity')->default(0);

            // Ini kolom 'movement_type' dan 'note' dari kodingan kamu yang lama
            $table->enum('movement_type', ['in', 'out', 'preorder']);
            $table->text('note')->nullable();
            $table->text('description')->nullable();
                // KOLOM BARU: Balance (Saldo stok)
            $table->integer('balance_before')->default(0);
            $table->integer('balance_after')->default(0);
            $table->timestamps(); // Wajib ada created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kalau di-rollback, tabelnya dihapus
        Schema::dropIfExists('stock_movements');
    }
};
