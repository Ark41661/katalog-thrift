<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Gamifikasi: badges member
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('badge_type'); // reviewer, photographer, collector, social, early_adopter
            $table->string('badge_name');
            $table->string('badge_icon')->nullable();
            $table->text('criteria')->nullable();
            $table->timestamp('earned_at')->useCurrent();
            $table->unique(['user_id', 'badge_name']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Log aktivitas untuk poin
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('activity_type'); // review, ugc_submit, wishlist, follow, question, share
            $table->text('description')->nullable();
            $table->integer('points_earned')->default(0);
            $table->morphs('referenceable'); // produk/outfit dll
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Wishlist stats tracking (per partner untuk analytics)
        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('is_approved')->default(true)->after('comment');
            $table->text('admin_note')->nullable()->after('is_approved');
            $table->timestamp('moderated_at')->nullable()->after('admin_note');
            $table->unsignedBigInteger('moderated_by')->nullable()->after('moderated_at');
        });

        // Add resolved_by to reports
        Schema::table('product_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('resolved_by')->nullable()->after('detail');
            $table->text('resolution_note')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('activity_logs');
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['is_approved', 'admin_note', 'moderated_at', 'moderated_by']);
        });
        Schema::table('product_reports', function (Blueprint $table) {
            $table->dropColumn(['resolved_by', 'resolution_note']);
        });
    }
};
