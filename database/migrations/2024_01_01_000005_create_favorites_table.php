<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('univers_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Un utilisateur ne peut favoriser qu'une fois la même carte
            $table->unique(['user_id', 'univers_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
