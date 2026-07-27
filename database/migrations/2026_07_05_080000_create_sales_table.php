<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('invoice_number');

            $table->string('customer_name');

            $table->json('products');

            $table->decimal('total_price',15,2)
                ->default(0);

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};