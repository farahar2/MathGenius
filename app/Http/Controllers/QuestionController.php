<?php

namespace App\Http\Controllers;

use App\Http\Requests\Question\StoreQuestionRequest;
use App\Http\Requests\Question\UpdateQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuestionController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $questions = Question::query()
            ->when($request->has('id_quiz'), fn ($q) => $q->where('id_quiz', $request->integer('id_quiz')))
            ->orderBy('ordre')
            ->get();

        return QuestionResource::collection($questions);
    }

    public function indexByQuiz(Quiz $quiz): AnonymousResourceCollection
    {
        $questions = Question::where('id_quiz', $quiz->id)
            ->orderBy('ordre')
            ->get();

        return QuestionResource::collection($questions);
    }

    public function store(StoreQuestionRequest $request): JsonResponse
    {
<<<<<<< Updated upstream
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
=======
        $this->authorize('create', Question::class);

        $question = Question::create($request->validated());
>>>>>>> Stashed changes

        return (new QuestionResource($question))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Question $question): QuestionResource
    {
        return new QuestionResource($question->load('quiz'));
    }

    public function update(UpdateQuestionRequest $request, Question $question): QuestionResource
    {
<<<<<<< Updated upstream
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
=======
        $this->authorize('update', $question);

        $question->update($request->validated());
>>>>>>> Stashed changes

        return new QuestionResource($question);
    }

    public function destroy(Question $question): JsonResponse
    {
        $question->delete();

        return response()->json(null, 204);
    }
}
