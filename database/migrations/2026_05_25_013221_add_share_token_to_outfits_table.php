<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('outfits', function (Blueprint $table) {
            $table->string('share_token', 16)->nullable()->unique()->after('is_active');
            $table->unsignedBigInteger('partner_id')->nullable()->after('created_by_id');
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('outfits', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
            $table->dropColumn(['share_token', 'partner_id']);
        });
    }
};
