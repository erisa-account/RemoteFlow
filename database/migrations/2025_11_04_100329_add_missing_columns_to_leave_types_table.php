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
        Schema::table('leave_types', function (Blueprint $table) {
            $table->string('key')->unique(); // vacation, sick, personal, unpaid
            $table->string('display_name');
            $table->boolean('is_paid')->default(true);
            $table->boolean('requires_document')->default(false);
            $table->string('color')->nullable(); // e.g. #3b82f6 or token
        });
    }

    public function down(): void {
        Schema::table('leave_types', function (Blueprint $table) {
            $table->dropColumn([
                'key',
                'display_name',
                'is_paid',
                'requires_document',
                'color',
            ]);
        });
    } 
};
