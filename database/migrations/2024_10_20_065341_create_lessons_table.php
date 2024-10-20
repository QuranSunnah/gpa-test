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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->char('title', 255);
            $table->integer('section_id');
            $table->integer('course_id');
            $table->tinyInteger('morph_type')->comment('1=quize,2=exam,3=resource');
            $table->integer('morph_id');
            $table->integer('duration')->default(0);
            $table->json('media_info');
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
