<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('day_markers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');// the actual calendar day being marked
            $table->string('status');// HOLIDAY_WORKED | WEEKEND_WORKED | REPLACEMENT_OFF | REPLACEMENT_SOURCE
            $table->string('color')->nullable();// optional override color for UI
            // Optional references (not required, but handy to trace)
            $table->foreignId('leave_request_id')->nullable()->constrained()->nullOnDelete();
            $table->string('note')->nullable();// free text (e.g. “swapped with 2025-10-12”)
            $table->timestamps();

            $table->unique(['user_id','date','status']); // avoid duplicate tags
            $table->index(['user_id','date']);
            $table->index('status');
            }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_markers');
    }
};
