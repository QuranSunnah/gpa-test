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
            $table->tinyInteger('type')->comment('1=Youtube,2=Vimeo,3=Video file');
            $table->tinyInteger('morph_type')->comment('1=quize,2=exam,3=resource,4=video');
            $table->integer('morph_id')->default(0);
            $table->integer('duration')->default(0);
            $table->json('media_info')->nullable();
            $table->integer('order')->default(0);
            $table->text('summery')->nullable();
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
