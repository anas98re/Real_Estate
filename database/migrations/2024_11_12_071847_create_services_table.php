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
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('service_types')
                ->onDelete('cascade');

            $table->string('service_name', 250)->nullable();

            // $table->enum('type', [
            //     'residential',
            //     'commercial'
            // ])->default('residential')->nullable();

            // $table->enum('residential_type', [
            //     'Apartment',
            //     'Villa',
            //     'Townhouse',
            //     'Penthouse',
            //     'Villa Compound',
            //     'Hotel Apartment',
            //     'Residential Plot',
            //     'Residential Floor',
            //     'Residential Building'
            // ])->default('residential')->nullable();

            // $table->enum('commercial_type', [
            //     'Office',
            //     'Shop',
            //     'Warehouse',
            //     'Labour Camp',
            //     'Commercial Villa',
            //     'Bulk Unit',
            //     'Commercial Plot',
            //     'Commercial Floor',
            //     'Commercial Building',
            //     'Factory',
            //     'Industrial Land',
            //     'Mixed Use Land',
            //     'Showeroom',
            //     'Other Commercial'
            // ])->default('residential')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
