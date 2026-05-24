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
            $table->string('near_campus')->nullable()->after('max_people');
            $table->string('near_mall')->nullable()->after('near_campus');
            $table->string('near_hospital')->nullable()->after('near_mall');
            $table->string('near_station')->nullable()->after('near_hospital');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['near_campus', 'near_mall', 'near_hospital', 'near_station']);
        });
    }
};
