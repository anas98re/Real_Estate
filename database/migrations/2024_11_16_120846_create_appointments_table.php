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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('agent_id');
            $table->unsignedBigInteger ('realty_id');
            $table->unsignedBigInteger ('appointment_type_id');
            $table->date('appointment_date')->nullable();
            $table->String('status')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('realty_id')->references('id')->on('realties')->onDelete('cascade');
            $table->foreign('appointment_type_id')->references('id')->on('appointment_types')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
