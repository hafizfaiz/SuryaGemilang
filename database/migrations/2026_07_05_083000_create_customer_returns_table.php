<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_returns', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sales_id')
                ->constrained('sales')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->integer('return_quantity');

            $table->enum('return_reason', [
                'tidak_sesuai',
                'barang_cacat',
            ]);

            $table->enum('return_condition', [
                'layak',
                'tidak_layak',
            ]);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_returns');
    }
};