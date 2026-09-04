<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('body_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('measured_at');
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->decimal('body_fat_percent', 5, 2)->nullable();
            $table->decimal('muscle_mass_kg', 5, 2)->nullable();
            $table->decimal('waist_cm', 5, 2)->nullable();
            $table->decimal('chest_cm', 5, 2)->nullable();
            $table->decimal('hips_cm', 5, 2)->nullable();
            $table->decimal('arms_cm', 5, 2)->nullable();
            $table->decimal('thighs_cm', 5, 2)->nullable();
            $table->decimal('neck_cm', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('body_measurements');
    }
};
