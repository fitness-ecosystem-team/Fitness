<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('food_id')->constrained('foods')->onDelete('cascade');
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'snack']);
            $table->decimal('quantity_g', 7, 2);
            $table->decimal('calories', 7, 2);
            $table->decimal('protein', 6, 2)->default(0);
            $table->decimal('carbs', 6, 2)->default(0);
            $table->decimal('fat', 6, 2)->default(0);
            $table->date('logged_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_logs');
    }
};
