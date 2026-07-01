<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_sold')->default(false)->after('is_active');
            $table->boolean('is_new_arrival')->default(false)->after('is_sold');
            $table->text('story')->nullable()->after('description');
            $table->string('lookbook_image')->nullable()->after('story');
            $table->string('lookbook_style_tip')->nullable()->after('lookbook_image');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_sold', 'is_new_arrival', 'story', 'lookbook_image', 'lookbook_style_tip']);
        });
    }
};
