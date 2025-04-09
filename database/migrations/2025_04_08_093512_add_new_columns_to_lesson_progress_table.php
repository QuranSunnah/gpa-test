<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->after('total_marks', function (Blueprint $table) {
                $table->tinyInteger('type')->default(1)->comment('1=regular,2=bulk');
                $table->integer('status')->default(1)->comment('1=active,0=inactive');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->dropColumn(['type', 'status']);
        });
    }
};
