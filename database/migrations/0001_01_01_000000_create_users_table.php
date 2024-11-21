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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->char('first_name', 255);
            $table->char('last_name', 255)->nullable();
            $table->char('email', 150)->unique();
            $table->char('phone', 50)->nullable()->unique();
            $table->char('password', 60);
            $table->tinyInteger('gender')->nullable()->comment('1= Male, 2=Female, 3=Others');
            $table->char('fathers_name', 255)->nullable();
            $table->char('mothers_name', 255)->nullable();
            $table->tinyInteger('blood_group')->nullable()->comment('1=A+, 2=A-, 3=B+, 4=B-, 5=O+, 6=O-, 7=AB+, 8=AB-');
            $table->date('dob')->nullable();
            $table->char('religion', 50)->nullable();
            $table->json('images')->nullable();
            $table->char('address', 255)->nullable();
            $table->char('nationality', 255)->nullable();
            $table->tinyInteger('academic_status')
                ->nullable()
                ->comment('1=Graduated, 2=Post Graduated, 3=1-4th year university student, 4=Others');
            $table->integer('institute_id')->nullable();
            $table->char('institute_name', 255)->nullable();
            $table->tinyInteger('identification_type')->nullable();
            $table->char('identification_number', 150)->nullable();
            $table->json('social_links')->nullable();
            $table->tinyInteger('designation')->nullable()
                ->comment('1=Student,2=Service Holder,3=Self Employed,4=Others');
            $table->longText('about_yourself')->nullable();
            $table->longText('biography')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->json('settings')->nullable();
            $table->char('last_otp', 60)->nullable();
            $table->timestamp('otp_created_at')->nullable();
            $table->tinyInteger('is_verified')->default(0)->comment('0=No,1=Yes');
            $table->tinyInteger('verified_by')->nullable()->comment('1=manual,2=google');
            $table->tinyInteger('status')->default(1)->comment('0=Inactive,1=Active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
