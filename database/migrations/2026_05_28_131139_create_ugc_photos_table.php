<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ugc_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // null = anonim
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('submitter_name');
            $table->string('submitter_instagram')->nullable();
            $table->string('photo'); // path upload
            $table->text('caption')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
        });
    }
    public function down(): void { Schema::dropIfExists('ugc_photos'); }
};
