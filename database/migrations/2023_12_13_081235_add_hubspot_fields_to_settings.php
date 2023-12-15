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
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('hubspot_active')->default(0);
            $table->text('hubspot_api_key')->nullable();
            $table->text('hubspot_api_secret')->nullable();
            $table->text('hubspot_redirect_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('hubspot_active');
            $table->dropColumn('hubspot_api_key');
            $table->dropColumn('hubspot_api_secret');
            $table->dropColumn('hubspot_redirect_url');
        });
    }
};
