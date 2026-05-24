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
        Schema::table('listings', function (Blueprint $table) {
            $table->index('status');
            $table->index('city');
            $table->index('price');
            $table->index('gender_type');
            $table->index('is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['city']);
            $table->dropIndex(['price']);
            $table->dropIndex(['gender_type']);
            $table->dropIndex(['is_verified']);
        });
    }
};
