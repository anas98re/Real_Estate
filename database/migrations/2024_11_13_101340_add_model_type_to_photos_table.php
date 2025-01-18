<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->string('model_type');
            $table->index(['model_id', 'model_type']);
        });
    }

    public function down()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->dropColumn('model_type');
            $table->dropIndex(['model_id', 'model_type']);
        });
    }
};
