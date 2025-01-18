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
            $table->increments('id');
            $table->string('name', 100)->nullable();
            $table->string('username', 250)->nullable();
            $table->string('first_name', 250)->nullable();
            $table->string('second_name', 250)->nullable();
            $table->string('password', 500)->nullable();
            $table->enum('gender', ['0', '1'])->default('0');
            $table->string('email', 100)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('verification_expired_at')->nullable();
            $table->timestamp('OTP_verified_at')->nullable();
            $table->timestamp('OTP_expired_at')->nullable();
            $table->string('phone', 100)->nullable();
            $table->string('facebook_account', 500)->nullable();
            $table->string('instagram_account', 500)->nullable();
            $table->string('tiktok_account', 500)->nullable();
            $table->string('twitter_account', 500)->nullable();
            $table->date('birthday')->nullable();

            $table->unsignedInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->string('city', 150)->nullable();

            $table->dateTime('last_login')->nullable();
            $table->integer('active')->nullable();
            $table->integer('verified')->nullable();
            $table->string('verification_code', 50)->nullable();
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
            $table->string('created_by', 250)->nullable();
            $table->string('updated_by', 250)->nullable();
            $table->string('deleted_by', 250)->nullable();
            $table->integer('referred_by')->nullable();
            $table->string('fcm_token', 500)->nullable();
            $table->string('phone_code', 150)->nullable();
            $table->string('cover', 250)->nullable();

            // $table->unsignedInteger('role_id');
            // $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
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
