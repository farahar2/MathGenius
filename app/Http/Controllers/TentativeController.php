<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Tentative;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TentativeController extends Controller
{
    public function index(Request $request)
    {
        return Tentative::query()
            ->when($request->has('id_quiz'), fn ($q) => $q->where('id_quiz', $request->integer('id_quiz')))
            ->when($request->user(), fn ($q) => $q->where('id_utilisateur', $request->user()->id))
            ->orderByDesc('created_at')
            ->get();
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id_quiz' => ['required', 'exists:quiz,id'],
            'reponses' => ['required', 'array'],
            'reponses.*.id_question' => ['required', 'exists:questions,id'],
            'reponses.*.reponse_eleve' => ['required', 'in:A,B,C,D'],
        ]);

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

        return response()->json($tentative->load('reponses'), 201);
    }

    public function show(Tentative $tentative): JsonResponse
    {
        return response()->json($tentative->load(['quiz.questions', 'reponses']));
    }

    public function destroy(Tentative $tentative): JsonResponse
    {
        $tentative->delete();

        return response()->json(null, 204);
    }
}