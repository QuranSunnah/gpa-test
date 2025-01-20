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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->integer('instructor_id');
            $table->integer('category_id');
            $table->char('title', 255);
            $table->char('slug', 255)->unique();
            $table->tinyInteger('type')->default(1)->comment('1=regular,2=masterclass');
            $table->text('short_description')->nullable();
            $table->text('full_description')->nullable();
            $table->integer('duration')->default(0)->comment('in seconds');
            $table->json('outcomes')->nullable();
            $table->json('requirements')->nullable();
            $table->json('live_class')->nullable();
            $table->json('faq')->nullable();
            $table->tinyInteger('language')->default(1)->comment('1=English,2=Bangla');
            $table->float('price')->nullable();
            $table->float('discount')->nullable();
            $table->tinyInteger('level')->default(1)->comment('1=Beginner,2=Intermediate,3=Advanced');
            $table->integer('pass_marks');
            $table->boolean('is_certification_final_exam_required')->default(0)->comment('0=No,1=Yes');
            $table->json('media_info')->nullable();
            $table->json('others')->nullable();
            $table->boolean('is_top')->default(0)->comment('0=No,1=Yes');
            $table->tinyInteger('status')->default(1)->comment('1=Active,0=Inactive');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
