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
    Schema::create('remotive', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('status_id');
        $table->date('date');
        $table->timestamp('created_at')->useCurrent();

        // Foreign keys
        $table->foreign('user_id')->references('id')->on('users');
        $table->foreign('status_id')->references('id')->on('status');

        // Unique constraint: one status per user per day
        $table->unique(['user_id', 'date']);
    });
}
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remotive');
    }
};
