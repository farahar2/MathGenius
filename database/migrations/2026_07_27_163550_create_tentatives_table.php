<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tentatives', function (Blueprint $table) {
            $table->id();
            $table->integer('score')->default(0);
            $table->decimal('score_pct', 5, 2)->default(0.00);
            $table->text('analyse_ia')->nullable();
            $table->text('recomm_ia')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('id_quiz')->constrained('quiz')->cascadeOnDelete();
            $table->foreignId('id_utilisateur')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tentatives');
    }
};
