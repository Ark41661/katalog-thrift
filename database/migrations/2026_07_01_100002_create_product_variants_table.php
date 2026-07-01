<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('size'); // S, M, L, XL
            $table->integer('price')->nullable(); // override price jika berbeda
            $table->string('condition')->nullable();
            $table->integer('stock')->default(1);
            $table->boolean('is_sold')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unique(['product_id', 'size']);
        });

        // Add parent_product_id to products for variant grouping
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_product_id')->nullable()->after('id');
            $table->string('size_display')->nullable()->after('size');
            $table->integer('stock')->default(1)->after('is_sold');
            $table->boolean('has_variants')->default(false)->after('is_sold');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['parent_product_id', 'size_display', 'stock', 'has_variants']);
        });
    }
};
