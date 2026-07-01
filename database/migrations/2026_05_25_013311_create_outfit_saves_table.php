<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outfit_saves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('outfit_id');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['user_id', 'outfit_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('outfit_id')->references('id')->on('outfits')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outfit_saves');
    }
};
