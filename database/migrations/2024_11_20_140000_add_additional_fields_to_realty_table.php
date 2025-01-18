<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('realties', function (Blueprint $table) {
            $table->string('country')->after('location')->nullable();
            $table->string('city')->after('country')->nullable();
            $table->integer('num_rooms')->nullable();
            $table->integer('num_bathrooms')->nullable();
            $table->integer('space_sqft')->nullable();
            $table->integer('year_built')->nullable();
            $table->integer('parking_spaces')->nullable();
            $table->integer('lot_size')->nullable();
        });
    }

    public function down()
    {
        Schema::table('realty', function (Blueprint $table) {
            $table->dropColumn('country');
            $table->dropColumn('city');
            $table->dropColumn('num_rooms');
            $table->dropColumn('num_bathrooms');
            $table->dropColumn('space_sqft');
            $table->dropColumn('year_built');
            $table->dropColumn('parking_spaces');
            $table->dropColumn('lot_size');
        });
    }
};
