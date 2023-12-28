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
        Schema::table('verticals', function (Blueprint $table) {
            $table->dropIndex('verticals_slug_unique');
            $table->text('slug')->change();
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verticals', function (Blueprint $table) {
            $table->string('slug')->change();
        });
    }
};
