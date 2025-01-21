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
        Schema::create('web_pages', function (Blueprint $table) {
            $table->id();
            $table->char('title', 255);
            $table->char('slug', 255);
            $table->integer('status')->default(1)->comment('1=active,0=inactive');
            $table->integer('lang')->default(1)->comment('1=en,2=bn');
            $table->json('components')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['slug', 'lang'], 'slug_lang_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_pages');
    }
};
