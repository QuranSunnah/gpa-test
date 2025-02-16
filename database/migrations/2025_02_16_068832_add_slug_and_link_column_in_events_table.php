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
                $table->text('link')->nullable();
                $table->json('gallery')->nullable();
            });
            $table->renameColumn('image', 'banner');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['slug', 'link', 'gallery']);
            $table->renameColumn('banner', 'image');
        });
    }
};
