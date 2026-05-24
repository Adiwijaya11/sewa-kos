<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'success', 'failed', 'cancelled'])->default('pending')->change();
            $table->unsignedBigInteger('cancellation_fee')->default(0)->after('va_number');
            $table->timestamp('cancelled_at')->nullable()->after('cancellation_fee');
            $table->string('cancellation_reason')->nullable()->after('cancelled_at');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['cancellation_fee', 'cancelled_at', 'cancellation_reason']);
        });
    }
};
