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
        Schema::create('realties', function (Blueprint $table) {
            $table->id();
            $table->json('name')->nullable();
            $table->string('photo')->nullable();
            $table->string('price')->nullable();
            $table->json('description')->nullable();
            $table->json('location')->nullable();
            $table->text('features')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realties');
    }
};
