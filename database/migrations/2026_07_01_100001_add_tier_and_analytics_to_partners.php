<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->string('tier')->default('bronze')->after('is_verified'); // bronze, silver, gold, platinum
            $table->integer('total_views')->default(0)->after('tier');
            $table->integer('total_wa_clicks')->default(0)->after('total_views');
            $table->integer('total_wishlist_count')->default(0)->after('total_wa_clicks');
            $table->integer('follower_count')->default(0)->after('total_wishlist_count');
            $table->jsonb('analytics_data')->nullable()->after('follower_count');
        });
    }

    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn(['tier', 'total_views', 'total_wa_clicks', 'total_wishlist_count', 'follower_count', 'analytics_data']);
        });
    }
};
