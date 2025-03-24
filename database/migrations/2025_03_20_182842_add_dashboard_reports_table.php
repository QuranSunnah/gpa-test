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
        Schema::create('dashboard_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('total_enrollments');
            $table->integer('total_completions');
            $table->integer('total_students');
            $table->tinyInteger('gender')->default(1)->comment('1= Male, 2=Female, 3=Others');
            $table->date('date');
            $table->unique(['date', 'gender']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_reports');
    }
};
