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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->char('name', 255);
            $table->char('slug', 255)->unique()->nullable();
            $table->text('description')->nullable();
            $table->integer('parent')->default(0)->comment('category_id');
            $table->char('image', 255)->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_top')->default(1);
            $table->boolean('is_highlighted')->default(0);
            $table->json('others')->nullable();
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
        Schema::dropIfExists('categories');
    }
};
