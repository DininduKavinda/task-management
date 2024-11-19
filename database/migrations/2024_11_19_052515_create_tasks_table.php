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
        Schema::create('tasks', function (Blueprint $table) {
            $table->foreignId('user_id');
            $table->increments('id');
            $table->string('title',50);
            $table->text('description')->nullable();
            $table->date('due_date');
            $table->enum('priority', ['High' , 'Medium','Low']);
            $table->boolean('is_complete')->default(false);
            $table->boolean('is_paid')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
