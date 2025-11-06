<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained('leave_types');

            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedSmallInteger('days'); // inclusive total (per your policy)
            $table->text('reason')->nullable();

            $table->string('status')->index(); // pending, approved, rejected, cancelled

            $table->foreignId('approver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->string('medical_certificate_path')->nullable();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamps();

             // Optional helper flags for comp-time usage (UI can send this):
            $table->boolean('uses_comp_time')->default(false);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
