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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('instructor_id')->comment('user_id');
            $table->char('title', 255);
            $table->char('slug', 255)->nullable();
            $table->text('short_description')->nullable();
            $table->text('full_descriptoin')->nullable();
            $table->integer('category_id');
            $table->tinyInteger('type')->nullable()->comment('1=free,2=paid,3=request,4=subscription');
            $table->integer('duration')->default(0);
            $table->json('curriculum')->nullable();
            $table->json('outcomes')->nullable();
            $table->json('requirements')->nullable();
            $table->json('live_class')->nullable();
            $table->json('tag')->nullable();
            $table->tinyInteger('language')->default(1);
            $table->float('price')->nullable();
            $table->float('discount')->nullable();
            $table->tinyInteger('level')->default(1);
            $table->integer('pass_marks');
            $table->boolean('is_certification_final_exam_required')->default(0);
            $table->json('media_info')->nullable();
            $table->json('others')->nullable();
            $table->boolean('is_top')->default(0);
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
        Schema::dropIfExists('courses');
    }
};
