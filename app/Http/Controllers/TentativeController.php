<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tentative\StoreTentativeRequest;
use App\Http\Resources\TentativeResource;
use App\Models\Quiz;
use App\Models\Tentative;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TentativeController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        // Filtre inconditionnel : un `when($request->user())` sauterait la
        // restriction si la requête n'était pas authentifiée et renverrait
        // les tentatives de tous les élèves.
        $tentatives = Tentative::query()
            ->where('id_utilisateur', $request->user()->id)
            ->when($request->has('id_quiz'), fn ($q) => $q->where('id_quiz', $request->integer('id_quiz')))
            ->with('quiz.lecon')
            ->orderByDesc('created_at')
            ->get();

        return TentativeResource::collection($tentatives);
    }

    public function store(StoreTentativeRequest $request): JsonResponse
    {
        $this->authorize('create', Tentative::class);

        $data = $request->validated();

        $quiz = Quiz::with('questions')->findOrFail($data['id_quiz']);
        $total = $quiz->questions->count();
        $bonnes = 0;

        $reponses = collect($data['reponses'])->map(function ($item) use ($quiz, &$bonnes) {
            $question = $quiz->questions->firstWhere('id', $item['id_question']);
            $estCorrecte = $question && $item['reponse_eleve'] === $question->bonne_reponse;
            $bonnes += $estCorrecte ? 1 : 0;

            return [
                'id_question' => $item['id_question'],
                'reponse_eleve' => $item['reponse_eleve'],
                'est_correcte' => $estCorrecte,
            ];
        })->all();

        $tentative = Tentative::create([
            'score' => $bonnes,
            'score_pct' => $total > 0 ? round($bonnes / $total * 100, 2) : 0.00,
            'completed_at' => now(),
            'id_quiz' => $quiz->id,
            'id_utilisateur' => $request->user()?->id,
        ]);

        foreach ($reponses as $reponse) {
            $tentative->reponses()->create($reponse);
        }

        return (new TentativeResource($tentative->load('reponses')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Tentative $tentative): TentativeResource
    {
        $this->authorize('view', $tentative);

        return new TentativeResource($tentative->load(['quiz.questions', 'reponses']));
    }

    public function destroy(Tentative $tentative): JsonResponse
    {
        $this->authorize('delete', $tentative);

        $tentative->delete();

        return response()->json(null, 204);
    }
}
