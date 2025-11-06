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
        Schema::table('leave_balances', function (Blueprint $table) {
            // Add columns
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('year');
            $table->unsignedSmallInteger('total_days');
            $table->unsignedSmallInteger('used_days')->default(0);
            $table->unsignedSmallInteger('carried_over_days')->default(0);

            // Optional: indexes & unique constraints
            $table->unique(['user_id', 'year']);
            $table->index(['user_id', 'year']);
        });
    }

    public function down(): void
    {
        Schema::table('leave_balances', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'year']);
            $table->dropIndex(['user_id', 'year']);
            $table->dropColumn([
                'user_id',
                'year',
                'total_days',
                'used_days',
                'carried_over_days',
            ]);
        });
    }
};
