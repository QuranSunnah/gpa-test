<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('enrolls', function (Blueprint $table) {
            $table->dropColumn(['start_at', 'end_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrolls', function (Blueprint $table) {
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
        });
    }
};
