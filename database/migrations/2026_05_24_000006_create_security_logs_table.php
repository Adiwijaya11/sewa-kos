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
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('event_type'); // login_failed, login_success, permanent_suspension, temporary_suspension, ip_banned, ip_unbanned, listing_suspended, manual_audit
            $table->text('description');
            $table->string('ip_address');
            $table->text('user_agent')->nullable();
            $table->string('risk_level'); // low, medium, high, critical
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_logs');
    }
};
