<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Pastikan ini ada

return new class extends Migration
{
    /**
     * Nama constraint yang dibuat Laravel untuk enum.
     * Format: [nama_tabel]_[nama_kolom]_check
     */
    // 🟢 TAMBAHKAN BARIS INI UNTUK MENDEFINISIKAN PROPERTI
    private string $constraintName = 'orders_payment_method_check'; 

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 2. Hapus constraint lama dan tambahkan yang baru (untuk PostgreSQL)
        try {
            DB::statement("ALTER TABLE orders DROP CONSTRAINT {$this->constraintName}");
        } catch (\Exception $e) {
            // Abaikan jika constraint tidak ada
            \Log::warning("Could not drop constraint: {$this->constraintName}. May not exist.");
        }

        DB::statement("
            ALTER TABLE orders 
            ADD CONSTRAINT {$this->constraintName} 
            CHECK (payment_method IN ('cash', 'kjp', 'transfer_bank', 'e_wallet'))
        ");

        // 3. Tambahkan kolom baru (ini sudah benar)
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'proof_of_payment')) {
                $table->string('proof_of_payment')
                      ->nullable()
                      ->after('payment_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 4. Kembalikan constraint ke kondisi semula
        try {
            DB::statement("ALTER TABLE orders DROP CONSTRAINT {$this->constraintName}");
        } catch (\Exception $e) {
             // Abaikan
        }
        
        DB::statement("
            ALTER TABLE orders 
            ADD CONSTRAINT {$this->constraintName} 
            CHECK (payment_method IN ('cash', 'kjp'))
        ");

        // 5. Hapus kolom baru (ini sudah benar)
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'proof_of_payment')) {
                $table->dropColumn('proof_of_payment');
            }
        });
    }
};