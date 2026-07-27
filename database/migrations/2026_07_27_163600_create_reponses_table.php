<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reponses', function (Blueprint $table) {
            $table->id();
            $table->enum('reponse_eleve', ['A', 'B', 'C', 'D'])->nullable();
            $table->boolean('est_correcte')->default(false);
            $table->foreignId('id_quiz')->constrained('quiz')->cascadeOnDelete();
            $table->foreignId('id_question')->constrained('questions')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reponses');
    }
};
