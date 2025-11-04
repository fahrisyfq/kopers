<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Pastikan kolom category ada dan berupa string
            if (!Schema::hasColumn('products', 'category')) {
                $table->string('category')->default('Atribut Sekolah');
            } else {
                $table->string('category')->default('Atribut Sekolah')->change();
            }
        });

        // Hapus constraint lama kalau ada
        DB::statement('ALTER TABLE products DROP CONSTRAINT IF EXISTS products_category_check');

        // Tambahkan constraint baru
        DB::statement("ALTER TABLE products 
                       ADD CONSTRAINT products_category_check 
                       CHECK (category IN ('Atribut Sekolah', 'Seragam Sekolah'))");
    }

    public function down(): void
    {
        // Hapus constraint
        DB::statement('ALTER TABLE products DROP CONSTRAINT IF EXISTS products_category_check');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
