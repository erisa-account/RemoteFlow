<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('day_markers', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date'); // the actual calendar day being marked
            $table->string('status'); // HOLIDAY_WORKED | WEEKEND_WORKED | REPLACEMENT_OFF | REPLACEMENT_SOURCE
            $table->string('color')->nullable(); // optional override color for UI
            $table->foreignId('leave_request_id')->nullable()->constrained()->nullOnDelete();
            $table->string('note')->nullable(); // free text (e.g., "swapped with 2025-10-12")

            $table->unique(['user_id','date','status']); // avoid duplicate tags
            $table->index(['user_id','date']);
            $table->index('status');
        });
    }

    public function down(): void {
        Schema::table('day_markers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['leave_request_id']);
            $table->dropUnique(['user_id','date','status']);
            $table->dropIndex(['user_id','date']);
            $table->dropIndex(['status']);
            $table->dropColumn([
                'user_id',
                'date',
                'status',
                'color',
                'leave_request_id',
                'note'
            ]);
        });
    } 
};
