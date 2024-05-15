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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username', 50)->unique()->nullable(false);
            $table->string('email', 255)->unique()->nullable(false);
            $table->string('password', 255)->nullable();
            $table->text('avatar')->nullable();
            $table->string('first_name', 100)->nullable(false);
            $table->string('last_name', 100)->nullable(false);
            $table->smallInteger('birthday_day')->nullable();
            $table->smallInteger('birthday_month')->nullable();
            $table->smallInteger('birthday_year')->nullable();
            $table->string('province', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('ward', 100)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('phone', 50)->unique()->nullable();
            $table->tinyInteger('gender')->nullable(false)->comment('0: female; 1: male: 2: other');
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('status')->default(false)->nullable(false)->comment('true: enabled; false: disabled');
            // $table->rememberToken();
            $table->string('verify_user_token', 255)->nullable();
            $table->timestamp('user_verify_token_expiration')->nullable();
            $table->timestamps();
            $table->uuid('created_by');
            $table->uuid('updated_by');
            $table->string('creator_name', 50);
            $table->string('updater_name', 50);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
