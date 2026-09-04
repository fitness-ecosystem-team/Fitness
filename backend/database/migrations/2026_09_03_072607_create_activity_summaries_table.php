<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date')->index();
            $table->integer('steps')->default(0);
            $table->decimal('calories_burned', 7, 2)->default(0);
            $table->integer('active_minutes')->default(0);
            $table->decimal('distance_km', 7, 3)->default(0);
            $table->integer('water_ml')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_summaries');
    }
};
