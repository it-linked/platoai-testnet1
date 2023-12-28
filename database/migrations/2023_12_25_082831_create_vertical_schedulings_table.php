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
        Schema::create('vertical_schedulings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('sitemaps')->nullable();
            $table->text('source_link');
            $table->text('cron_interval');
            $table->enum( 'status', ['running', 'stop']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vertical_schedulings');
    }
};
