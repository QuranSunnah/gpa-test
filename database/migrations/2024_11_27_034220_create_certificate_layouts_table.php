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
        Schema::create('certificate_layouts', function (Blueprint $table) {
            $table->id();
            $table->char('title', 255);
            $table->char('path', 255);
            $table->integer('height')->default(0);
            $table->integer('width')->default(0);
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
        Schema::dropIfExists('certificate_layouts');
    }
};
