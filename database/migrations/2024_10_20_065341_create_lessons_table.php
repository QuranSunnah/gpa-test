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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->char('title', 255);
            $table->integer('section_id');
            $table->integer('course_id');
            $table->char('contentable_type', 255)->nullable()
                ->comment('App\Models\Quiz or App\Models\Examination or App\Models\Resource');
            $table->unsignedInteger('contentable_id')->nullable();
            $table->index(['contentable_type', 'contentable_id']);
            $table->integer('duration')->default(0);
            $table->json('media_info')->nullable();
            $table->integer('order')->default(0);
            $table->text('summary')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
