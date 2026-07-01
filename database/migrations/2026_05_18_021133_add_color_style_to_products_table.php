<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('color')->nullable()->after('brand');       // e.g. "orange", "black", "maroon"
            $table->string('color_hex')->nullable()->after('color');   // e.g. "#F97316"
            $table->string('style_type')->nullable()->after('color_hex'); // casual, streetwear, sporty
            $table->string('product_type')->default('hoodie')->after('style_type'); // hoodie, pants, jacket
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['color', 'color_hex', 'style_type', 'product_type']);
        });
    }
};
