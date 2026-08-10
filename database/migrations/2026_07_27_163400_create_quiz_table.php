<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_lecon')->constrained('lecons')->cascadeOnDelete();
            $table->foreignId('id_chapitre')
                  ->nullable()
                  ->constrained('chapitres')
                  ->nullOnDelete();
            $table->enum('difficulte', ['facile', 'moyen', 'difficile'])
                  ->default('moyen');
            $table->enum('niveau', ['debutant', 'intermediaire', 'avance'])
                  ->nullable();
            $table->unsignedInteger('duree_secondes')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz');
    }
};
