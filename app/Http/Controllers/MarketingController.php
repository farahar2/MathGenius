<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class MarketingController extends Controller
{
    public function landing(): View
    {
        return view('pages.landing', [
            'pipeline' => [
                ['n' => '01', 'glyph' => '📘', 'title' => 'Choisir un chapitre', 'body' => 'Leçons, exercices et corrigés organisés par niveau scolaire.'],
                ['n' => '02', 'glyph' => '✦', 'title' => "L'IA génère le quiz", 'body' => '20 questions QCM de difficulté moyenne, extraites des leçons du chapitre.'],
                ['n' => '03', 'glyph' => '✓', 'title' => 'Correction automatique', 'body' => 'Score calculé et niveau déterminé : débutant, intermédiaire ou avancé.'],
                ['n' => '04', 'glyph' => '↗', 'title' => 'Recommandations', 'body' => 'Analyse des erreurs, chapitres faibles et plan de révision personnalisé.'],
            ],
            'features' => [
                ['glyph' => '⚿', 'title' => 'Authentification Sanctum', 'body' => 'Inscription, connexion par token et gestion du profil : nom, email, mot de passe et rôle.'],
                ['glyph' => '📖', 'title' => 'Contenu pédagogique', 'body' => 'Chapitres, leçons et exercices corrigés avec support texte, images et formules.'],
                ['glyph' => '✦', 'title' => 'Quiz généré par IA', 'body' => '20 questions QCM par chapitre, générées à partir du contenu réel des leçons.'],
                ['glyph' => '📊', 'title' => 'Analyse des performances', 'body' => 'Correction automatique, score, détermination du niveau et analyse des erreurs.'],
                ['glyph' => '⬒', 'title' => 'Tableau de bord', 'body' => 'Historique, score moyen, progression et chapitres à revoir.'],
                ['glyph' => '⚙', 'title' => 'Espace formateur', 'body' => 'Gestion des chapitres, leçons, exercices et utilisateurs de la plateforme.'],
            ],
        ]);
    }

    public function showLogin(): View
    {
        return view('pages.auth', ['mode' => 'login']);
    }

    public function showRegister(): View
    {
        return view('pages.auth', ['mode' => 'register']);
    }

    public function dashboard(): View
    {
        return view('pages.dashboard-stub');
    }
}
