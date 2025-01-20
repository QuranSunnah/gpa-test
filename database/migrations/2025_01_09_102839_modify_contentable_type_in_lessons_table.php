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
        Schema::table('lessons', function (Blueprint $table) {
            $table->tinyInteger('contentable_type')->default(1)->comment('1=Lesson, 2=Quiz, 3=Resource')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->char('contentable_type', 255)
                ->nullable()->comment('App\Models\Lesson or App\Models\Quiz or App\Models\Resource')->change();
        });
    }
};
