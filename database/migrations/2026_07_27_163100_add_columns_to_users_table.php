<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('prenom', 100)->after('name');
            $table->enum('role', ['student', 'formateur'])
                  ->default('student')
                  ->after('prenom');
            $table->boolean('is_premium')
                  ->default(false)
                  ->after('role');
            $table->foreignId('filiere_id')
                  ->nullable()
                  ->after('is_premium')
                  ->constrained('filieres')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['filiere_id']);
            $table->dropColumn([
                'prenom',
                'role',
                'is_premium',
                'filiere_id',
            ]);
        });
    }
};
