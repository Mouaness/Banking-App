<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    //  CREATE TABLE requests (
    //     id INT NOT NULL AUTO_INCREMENT,
    //     user_id INT NOT NULL,
    //     type ENUM('account creation','deposit', 'withdrawal', 'transfer') NOT NULL,
    //     currency ENUM('LBP', 'EUR', 'USD') NOT NULL,
    //     amount INT NOT NULL,
    //     timestamp VARCHAR(255) DEFAULT now() NOT NULL,
    //     status ENUM('pending', 'accepted', 'rejected') NOT NULL,
    //     PRIMARY KEY (id),
    //     FOREIGN KEY (user_id) REFERENCES users(id)
    // );

    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('receiver_id')->nullable()->constrained('users');
            $table->enum('type', ['account creation', 'deposit', 'withdrawal', 'transfer']);
            $table->enum('currency', ['LBP', 'EUR', 'USD']);
            $table->integer('amount');
            $table->enum('status', ['pending', 'accepted', 'rejected']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
