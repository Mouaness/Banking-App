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
        Schema::create('users_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['deposit', 'withdraw', 'transfer']);
            $table->foreignId('user_id');
            $table->string('username');
            $table->foreignId('account_id');
            $table->string('account_name');
            $table->string('account_number');
            $table->foreignId('receiver_id')->nullable();
            $table->string('receiver_username')->nullable();
            $table->foreignId('receiver_account_id')->nullable();
            $table->string('receiver_account_name')->nullable();
            $table->string('receiver_account_number')->nullable();
            $table->decimal('amount', 20, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_transactions');
    }
};
