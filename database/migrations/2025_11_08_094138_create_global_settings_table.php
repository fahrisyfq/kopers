<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\GlobalSetting; // Pastikan ini di-import jika Anda langsung memanggil create()

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('global_settings', function (Blueprint $table) {
            $table->id();
            // Kolom untuk status toko, default AKTIF (true)
            $table->boolean('is_store_open')->default(true); 
            $table->timestamps();
        });

        // 💡 Tambahkan satu baris data awal agar setting langsung berfungsi
        \App\Models\GlobalSetting::create(['is_store_open' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_settings');
    }
};