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
        Schema::create('sitemap_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sitemap_id');
            $table->foreign('sitemap_id')->references('id')->on('sitemaps')->nullable();
            $table->text('url');
            $table->enum( 'frequency', ['daily', 'weekly', 'monthly', 'yearly']);
            $table->string('priority');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sitemap_pages');
    }
};
