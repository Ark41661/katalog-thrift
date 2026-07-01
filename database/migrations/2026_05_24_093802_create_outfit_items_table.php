<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outfit_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('outfit_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('sort_order')->default(0);
            $table->string('note')->nullable();
            $table->timestamps();
            $table->foreign('outfit_id')->references('id')->on('outfits')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outfit_items');
    }
};
