<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // JSON array: [{"label":"Celana","item":"Cargo Pants Hitam","image":"https://..."},...]
            $table->json('lookbook_pairing')->nullable()->after('lookbook_style_tip');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('lookbook_pairing');
        });
    }
};
