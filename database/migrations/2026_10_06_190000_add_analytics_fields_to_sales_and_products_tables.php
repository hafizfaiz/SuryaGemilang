<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'region')) {
                $table->string('region')->nullable()->default('Jawa Barat')->after('customer_name');
            }
            if (!Schema::hasColumn('sales', 'customer_segment')) {
                $table->string('customer_segment')->nullable()->default('Retail')->after('region');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'cost_price')) {
                $table->decimal('cost_price', 12, 2)->nullable()->after('price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'customer_segment')) {
                $table->dropColumn('customer_segment');
            }
            if (Schema::hasColumn('sales', 'region')) {
                $table->dropColumn('region');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'cost_price')) {
                $table->dropColumn('cost_price');
            }
        });
    }
};
