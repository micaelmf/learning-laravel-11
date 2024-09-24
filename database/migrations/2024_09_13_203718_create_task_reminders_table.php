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
        Schema::create('task_reminders', function (Blueprint $table) {
            $table->id();
            $table->string('preset_time');
            $table->timestamp('reminder_time')->nullable();
            $table->enum('send_by', ['popup', 'email', 'sms'])->default('popup');
            $table->enum('status', ['pending', 'canceled', 'sent', 'visualized'])->default('pending');
            $table->string('job_id')->nullable();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_reminders');
    }
};
