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
        Schema::create('defix_gateways', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("slug");
            $table->text("external_link")->nullable();
            $table->boolean("status")->default(1);
            $table->unsignedBigInteger('parent_id')->nullable(); // Added parent_id column
            $table->timestamps();
    
            // Foreign key constraint to create the parent-child relationship
            $table->foreign('parent_id')->references('id')->on('defix_gateways')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defix_gateways');
    }
};
