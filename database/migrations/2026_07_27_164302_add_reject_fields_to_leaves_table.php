<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->text('reject_reason')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('decision_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn([
                'reject_reason',
                'admin_notes',
                'decision_at'
            ]);
        });
    }
};