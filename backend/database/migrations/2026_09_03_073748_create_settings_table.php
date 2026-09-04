<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            $table->enum('language', ['en', 'ar', 'fr', 'es'])->default('en');
            $table->enum('weight_unit', ['kg', 'lbs'])->default('kg');
            $table->enum('distance_unit', ['km', 'miles'])->default('km');
            $table->enum('theme', ['light', 'dark', 'system'])->default('system');
            $table->boolean('notifications_enabled')->default(true);
            $table->boolean('workout_reminders')->default(true);
            $table->boolean('nutrition_reminders')->default(true);
            $table->boolean('sleep_reminders')->default(true);
            $table->boolean('streak_alerts')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
