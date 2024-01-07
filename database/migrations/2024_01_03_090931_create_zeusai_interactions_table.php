<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('zeusai_interactions', function (Blueprint $table) {
            $table->id();
            $table->string('conversation_id');
            $table->text('user_message');
            $table->text('chatgpt_response');
            $table->string('credits');
            $table->string('words');
            $table->timestamps();

            $table->foreign('conversation_id')->references('id')->on('zeusai_conversations');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zeusai_interactions');
    }
};
