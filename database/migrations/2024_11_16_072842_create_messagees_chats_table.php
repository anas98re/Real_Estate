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
        Schema::create('messagees_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sender_id');
            $table->unsignedInteger('receiver_id')->nullable();
            $table->unsignedBigInteger('chat_room_id');
            $table->text('message');
            $table->timestamp('sent_at')->useCurrent();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('chat_room_id')->references('id')->on('chat_rooms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messagees_chats');
    }
};
