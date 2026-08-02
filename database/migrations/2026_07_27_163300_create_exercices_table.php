<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercices', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->longText('enonce');
            $table->longText('correction');
            $table->string('image')->nullable();
            $table->string('fichier_pdf')->nullable();
            $table->integer('ordre')->default(0);
            $table->boolean('is_published')->default(false);
            $table->foreignId('id_lecon')->constrained('lecons')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercices');
    }
};
