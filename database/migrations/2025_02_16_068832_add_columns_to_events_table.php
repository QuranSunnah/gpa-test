<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->after('title', function (Blueprint $table) {
                $table->char('slug', 255)->unique();
                $table->integer('gallery_id')->nullable();
                $table->char('button_title', 255)->nullable();
                $table->char('button_url', 255)->nullable();
            });
            $table->renameColumn('image', 'banner');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('banner', 'image');
            $table->dropColumn(['slug', 'gallery_id', 'button_title', 'button_url']);
        });
    }
};
