<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
USE Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leave_balances', function (Blueprint $table) {
            $table->integer('carried_over_days')->change();
        });
        DB::statement("ALTER TABLE leave_balances CHANGE COLUMN carried_over_days remaining_days INT NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
            DB::statement("ALTER TABLE leave_balances CHANGE COLUMN remaining_days carried_over_days INT NOT NULL");
       
    }
};
