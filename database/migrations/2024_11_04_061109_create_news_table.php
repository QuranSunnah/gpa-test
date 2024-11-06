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
        Schema::create('news', function (Blueprint $table) {
            $table->id('id');
            $table->char('title', 255);
            $table->char('slug', 255)->unique();
            $table->text('description');
            $table->char('image', 255);
            $table->tinyInteger('is_highlighted')->default(0);
            $table->tinyInteger('status')->default(1)->comment('0=Inactive,1=Active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
