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
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('title', 255);
            $table->text('description')->nullable();
            $table->char('image', 255)->nullable();
            $table->date('date')->nullable();
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
        Schema::dropIfExists('events');
    }
};
