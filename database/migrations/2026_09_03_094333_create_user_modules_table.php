<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('module_name', [
                'nutrition',
                'fitness',
                'weight_body',
                'sleep_recovery',
                'mental_wellness',
                'habits_lifestyle',
                'beauty_selfcare',
                'preventive_wellness',
                'womens_wellness',
                'mens_wellness',
                'family_wellness',
                'workplace_wellness',
                'coaching',
                'connected_health',
                'community',
                'education',
            ]);
            $table->boolean('is_active')->default(false);
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'module_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_modules');
    }
};
