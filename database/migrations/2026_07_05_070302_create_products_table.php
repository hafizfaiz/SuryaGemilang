<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            $table->string('product_name');

            $table->string('type_name');

            $table->decimal('price', 12, 2);

            $table->integer('stock')
                ->default(0);

            // Safety Stock (SS)
            $table->integer('safety_stock')
                ->default(0);

            // Pemakaian rata-rata per hari (D)
            $table->decimal('daily_usage', 10, 2)
                ->default(0);

            // Lead Time (T)
            $table->integer('lead_time')
                ->default(15);

            // ROP = D x T + SS
            $table->integer('reorder_point')
                ->default(0);

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};