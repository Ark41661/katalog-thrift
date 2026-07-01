<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('outfit_items', function (Blueprint $table) {
            // Posisi hotspot dalam persen (0-100) dari pojok kiri atas foto
            $table->decimal('hotspot_x', 5, 2)->nullable()->after('note'); // % dari kiri
            $table->decimal('hotspot_y', 5, 2)->nullable()->after('hotspot_x'); // % dari atas
        });
    }
    public function down(): void {
        Schema::table('outfit_items', function (Blueprint $table) {
            $table->dropColumn(['hotspot_x', 'hotspot_y']);
        });
    }
};
