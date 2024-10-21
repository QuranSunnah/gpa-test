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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->json('social_info')->nullable();
            $table->json('contact_info')->nullable();
            $table->json('about_us')->nullable();
            $table->json('system_settings')->nullable();
            $table->text('guideline')->nullable();
            $table->tinyInteger('status')->default(config('common.status.active'))->comment('1=active,0=inactive');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
