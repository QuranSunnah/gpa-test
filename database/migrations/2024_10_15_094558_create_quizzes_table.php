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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->char('title', 255);
            $table->integer('course_id');
            $table->integer('category_id');
            $table->tinyInteger('type')->comment('1=random,2=manual');
            $table->json('questions_ids')->nullable();
            $table->integer('total_question')->nullable();
            $table->decimal('each_qmark', 10, 2)->nullable();
            $table->integer('pass_marks_percentage')->nullable();
            $table->integer('quiz_time')->default(0);
            $table->integer('attempt_time')->default(0);
            $table->integer('penalty_time')->default(0);
            $table->text('instructions')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
