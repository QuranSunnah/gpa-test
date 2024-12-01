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
        Schema::create('certificate_teamplates', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id');
            $table->integer('certificate_layout_id');
            $table->json('course_title');
            $table->json('student_name');
            $table->json('date');
            $table->tinyInteger('status')->default(1)->comment('0=Inactive,1=Active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_teamplates');
    }
};
