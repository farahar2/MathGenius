<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommandations', function (Blueprint $table) {
            $table->id();
            $table->text('message')->nullable();
            $table->boolean('is_lue')->default(false);
            $table->foreignId('id_chapitre')->constrained('chapitres')->cascadeOnDelete();
            $table->foreignId('id_utilisateur')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommandations');
    }
};
