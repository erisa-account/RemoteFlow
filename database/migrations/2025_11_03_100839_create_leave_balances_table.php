<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
        public function up(): void {
            Schema::create('leave_balances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->unsignedSmallInteger('year');
                $table->unsignedSmallInteger('total_days');
                $table->unsignedSmallInteger('used_days')->default(0);
                $table->unsignedSmallInteger('carried_over_days')->default(0);
                $table->timestamps();

                $table->unique(['user_id','year']);
                $table->index(['user_id','year']);
                });
        }   
    /**
     * Reverse the migrations. 
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_balances');
    }
};
