<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        return Question::query()
            ->when($request->has('id_quiz'), fn ($q) => $q->where('id_quiz', $request->integer('id_quiz')))
            ->orderBy('ordre')
            ->get();
    }

    public function indexByQuiz(Quiz $quiz)
    {
        return Question::where('id_quiz', $quiz->id)
            ->orderBy('ordre')
            ->get();
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'question' => ['required', 'string'],
            'option_a' => ['required', 'string', 'max:500'],
            'option_b' => ['required', 'string', 'max:500'],
            'option_c' => ['required', 'string', 'max:500'],
            'option_d' => ['required', 'string', 'max:500'],
            'bonne_reponse' => ['required', 'in:A,B,C,D'],
            'explication' => ['nullable', 'string'],
            'notion' => ['nullable', 'string'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'id_quiz' => ['required', 'exists:quiz,id'],
        ]);

        return response()->json(Question::create($data), 201);
    }

    public function show(Question $question): JsonResponse
    {
        return response()->json($question->load('quiz'));
    }

    public function update(Request $request, Question $question): JsonResponse
    {
        $data = $request->validate([
            'question' => ['sometimes', 'string'],
            'option_a' => ['sometimes', 'string', 'max:500'],
            'option_b' => ['sometimes', 'string', 'max:500'],
            'option_c' => ['sometimes', 'string', 'max:500'],
            'option_d' => ['sometimes', 'string', 'max:500'],
            'bonne_reponse' => ['sometimes', 'in:A,B,C,D'],
            'explication' => ['nullable', 'string'],
            'notion' => ['nullable', 'string'],
            'ordre' => ['sometimes', 'integer', 'min:0'],
            'id_quiz' => ['sometimes', 'exists:quiz,id'],
        ]);

        $question->update($data);

        return response()->json($question);
    }

    public function destroy(Question $question): JsonResponse
    {
        $question->delete();

        return response()->json(null, 204);
    }
}