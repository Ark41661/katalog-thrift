<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_status_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_report_id');
            $table->string('status');
            $table->unsignedBigInteger('action_by')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('product_report_id')
                ->references('id')
                ->on('product_reports')
                ->onDelete('cascade');

            $table->foreign('action_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_status_history');
    }
};
