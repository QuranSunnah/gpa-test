<?php

use App\Constants\StaticConstant;
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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->char('title', 255);
            $table->integer('category_id');
            $table->tinyInteger('type')->comment('1=text,2=single,3=multiple');
            $table->json('options')->nullable();
            $table->char('answers', 255);
            $table->json('feedbacks')->nullable();
            $table->integer('time_limit')->nullable();
            $table->tinyInteger('status')->default(StaticConstant::STATUS_ACTIVE); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
