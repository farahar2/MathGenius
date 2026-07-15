# AGENT.md — MathGenius

## Contexte projet

MathGenius est une plateforme web intelligente destinée aux lycéens marocains (Tronc Commun, 1ère année Bac, 2ème année Bac). Elle centralise leçons, exercices, corrigés et quiz générés par l'IA. L'objectif est d'améliorer les résultats scolaires via un apprentissage personnalisé.

L'IA analyse les performances après chaque quiz et recommande les chapitres à réviser en priorité.

### Public cible

- Tronc Commun
- 1ère année Baccalauréat
- 2ème année Baccalauréat

---

## Stack technique

| Couche | Technologie |
|---|---|
| Front-end | React.js |
| Back-end | Laravel 13 |
| API | REST API |
| Auth | Laravel Sanctum |
| Base de données | MySQL |
| IA | SDK `laravel/ai` + Groq |
| Tests | Pest |
| Conteneurisation | Docker + Docker Compose |
| CI/CD | GitHub Actions |
| Déploiement | Azure |
| Documentation API | Scribe |

---

## Architecture

```
Laravel API (REST)  ←→  React Frontend
        ↕
     MySQL
        ↕
   Queue / Jobs / Worker  →  SDK laravel/ai  →  Groq
        ↕
  GitHub → GitHub Actions → Docker → Azure
```

---

## Rôles

### Élève

- Créer un compte, se connecter, modifier son profil
- Consulter les leçons et exercices
- Passer des quiz IA
- Consulter son historique et son tableau de bord
- Recevoir des recommandations IA

### Administrateur

- Gérer les utilisateurs, chapitres, leçons, exercices
- Consulter les statistiques

---

## Fonctionnalités principales

1. Authentification
2. Gestion du profil
3. Gestion des chapitres
4. Gestion des leçons
5. Gestion des exercices
6. Génération de quiz IA
7. Correction automatique
8. Analyse IA
9. Recommandations IA
10. Historique
11. Dashboard
12. Administration

---

## Organisation Laravel

```
app/
├── Actions/          # Actions unitaires (une seule responsabilité)
├── Services/         # Logique métier complexe
├── Http/
│   ├── Controllers/  # Contrôleurs légers (délèguent aux Services/Actions)
│   ├── Requests/     # Form Requests (validation + autorisation)
│   └── Resources/    # API Resources (transformation JSON)
├── Models/
├── Policies/         # Authorizations
└── Jobs/             # Traitements asynchrones (IA notamment)
```

### Règles

- **Controllers** : légers, ne contiennent aucune logique métier. Appellent un Service ou une Action, retournent une ressource.
- **Services** : logique métier réutilisable (ex: `QuizService`, `AnalysisService`).
- **Actions** : classes invocables (pattern `__invoke`) pour une opération unique (ex: `GenerateQuiz`, `AnalyzeAnswers`).
- **Form Requests** : toute validation dans une classe dédiée. Jamais de validation dans les contrôleurs.
- **API Resources** : toute transformation JSON dans une resource. Jamais de `toArray()` sur le modèle.
- **Policies** : toute autorisation dans une policy.
- **Jobs** : tout appel IA est asynchrone via un Job.
- **Repositories** : uniquement si apportent une réelle valeur (pas de sur-ingénierie).

---

## Bonnes pratiques

### Principes

- SOLID
- Clean Code
- PSR-12
- Conventions Laravel
- DRY, KISS, YAGNI

### API

- Toujours utiliser des **Form Requests** pour la validation
- Toujours utiliser des **API Resources** pour les réponses
- Codes HTTP corrects :
  - `200` OK
  - `201` Created
  - `202` Accepted (traitement asynchrone)
  - `401` Unauthorized
  - `403` Forbidden
  - `404` Not Found
  - `422` Validation Error
  - `500` Server Error

### IA

- Toujours utiliser le SDK `laravel/ai`
- Réponses en **Structured Output** (objet JSON valide)
- **Ne jamais parser du texte libre**
- Stocker les résultats en DB avec des **Casts** adaptés (array, json, object)
- Les appels IA sont **toujours asynchrones** : Job + Queue + Worker
- L'API retourne `202 Accepted` pour les traitements IA

### Queue

- Driver : `database` ou `redis`
- Les Jobs IA sont dispatchés sur une queue dédiée (ex: `ai`)
- Un Worker tourne en permanence dans Docker Compose

### Tests (Pest)

- Tester : endpoints, validation, auth, autorisations, Jobs
- `Queue::fake()` pour les jobs
- IA mockée (ne jamais appeler Groq dans les tests)
- Vérifier la structure JSON des réponses
- Nommage : `describe()->it()` ou `test()`

### Docker

- `Dockerfile` pour l'image Laravel
- `docker-compose.yml` avec services :
  - `laravel` (app)
  - `mysql`
  - `queue-worker`
  - `nginx` (optionnel)

### Documentation

- Code auto-documenté
- Endpoints documentés avec **Scribe**
- README maintenu à jour

### Git

- `feat:` nouvelle fonctionnalité
- `fix:` correction de bug
- `refactor:` refactoring
- `test:` tests
- `docs:` documentation
- `chore:` maintenance

---

## Workflow de développement

1. **Analyser** la demande
2. **Proposer** la solution
3. **Attendre validation** si la modification est importante
4. **Développer**
5. **Écrire les tests** (Pest)
6. **Vérifier la qualité** (lint, types, tests)
7. **Mettre à jour la documentation** (Scribe, README)

---

## Contraintes absolues

- Ne jamais générer de code inutile
- Toujours privilégier la simplicité
- Ne jamais casser une fonctionnalité existante
- Respecter la cohérence de l'architecture
- Ne jamais créer plusieurs façons de résoudre le même problème
- Toujours privilégier la solution la plus idiomatique Laravel
- Ne jamais appeler Groq dans les tests
- IA toujours asynchrone (Job + Queue)
- IA toujours en Structured Output
- Toujours utiliser Form Requests, API Resources, Policies

---

## Exemples concrets

### Form Request

```php
class StoreQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Quiz::class);
    }

    public function rules(): array
    {
        return [
            'chapter_id' => ['required', 'exists:chapters,id'],
            'difficulty' => ['required', 'in:facile,moyen,difficile'],
        ];
    }
}
```

### Action

```php
class GenerateQuiz
{
    public function __construct(
        private readonly QuizService $quizService,
    ) {}

    public function __invoke(Chapter $chapter, string $difficulty): void
    {
        $this->quizService->generateAndDispatch($chapter, $difficulty);
    }
}
```

### API Resource

```php
class QuizResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'questions' => $this->questions,
            'difficulty' => $this->difficulty,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
```

### Job IA avec Structured Output

```php
class GenerateQuizJob implements ShouldQueue
{
    public function handle(): void
    {
        $result = AI::chat()
            ->system('Génère un quiz en JSON valide')
            ->prompt('...')
            ->structuredOutput(QuizStructure::class)
            ->execute();

        Quiz::create([
            'chapter_id' => $this->chapter->id,
            'questions' => $result->questions,
            'difficulty' => $this->difficulty,
        ]);
    }
}
```

### Test Pest avec Queue fake

```php
it('dispatches a quiz generation job', function () {
    Queue::fake();

    $response = $this->actingAs($user)->postJson('/api/quizzes', [
        'chapter_id' => $chapter->id,
        'difficulty' => 'moyen',
    ]);

    $response->assertStatus(202);
    Queue::assertPushed(GenerateQuizJob::class);
});
```
