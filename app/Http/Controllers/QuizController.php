<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        return Quiz::query()
            ->when($request->has('id_lecon'), fn ($q) => $q->where('id_lecon', $request->integer('id_lecon')))
            ->when($request->has('id_chapitre'), fn ($q) => $q->where('id_chapitre', $request->integer('id_chapitre')))
            ->get();
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id_lecon' => ['required', 'exists:lecons,id'],
            'id_chapitre' => ['nullable', 'exists:chapitres,id'],
            'difficulte' => ['nullable', 'in:facile,moyen,difficile'],
            'niveau' => ['nullable', 'in:debutant,intermediaire,avance'],
            'duree_secondes' => ['nullable', 'integer', 'min:0'],
        ]);

        return response()->json(Quiz::create($data), 201);
    }

    public function show(Quiz $quiz): JsonResponse
    {
        return response()->json($quiz->load(['lecon', 'questions']));
    }

    public function update(Request $request, Quiz $quiz): JsonResponse
    {
        $data = $request->validate([
            'id_lecon' => ['sometimes', 'exists:lecons,id'],
            'id_chapitre' => ['nullable', 'exists:chapitres,id'],
            'difficulte' => ['sometimes', 'in:facile,moyen,difficile'],
            'niveau' => ['nullable', 'in:debutant,intermediaire,avance'],
            'duree_secondes' => ['sometimes', 'integer', 'min:0'],
        ]);

        $quiz->update($data);

        return response()->json($quiz);
    }

    public function destroy(Quiz $quiz): JsonResponse
    {
        $quiz->delete();

        return response()->json(null, 204);
    }
}