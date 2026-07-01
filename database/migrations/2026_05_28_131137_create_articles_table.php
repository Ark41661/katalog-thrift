<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('category'); // mix-match, tips-perawatan, tren, panduan
            $table->string('cover_image')->nullable();
            $table->text('excerpt')->nullable();
            $table->text('content'); // plain text, baris baru dirender dengan nl2br
            $table->string('author')->default('Admin');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->json('related_product_ids')->nullable(); // produk yang disebut di artikel
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('articles'); }
};
