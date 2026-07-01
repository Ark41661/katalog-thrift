<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('products', function (Blueprint $table) {
            // JSON: [{"label":"S","chest":"88","length":"65","shoulder":"42"},...]
            $table->json('size_chart')->nullable()->after('size');
            $table->string('size_unit')->default('cm')->after('size_chart');
        });
    }
    public function down(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['size_chart', 'size_unit']);
        });
    }
};
