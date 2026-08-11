<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quiz\StoreQuizRequest;
use App\Http\Requests\Quiz\UpdateQuizRequest;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuizController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $quiz = Quiz::query()
            ->when($request->has('id_lecon'), fn ($q) => $q->where('id_lecon', $request->integer('id_lecon')))
            ->when($request->has('id_chapitre'), fn ($q) => $q->where('id_chapitre', $request->integer('id_chapitre')))
            ->get();

        return QuizResource::collection($quiz);
    }

    public function store(StoreQuizRequest $request): JsonResponse
    {
        $this->authorize('create', Quiz::class);

        $quiz = Quiz::create($request->validated());

        return (new QuizResource($quiz))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Quiz $quiz): QuizResource
    {
        return new QuizResource($quiz->load(['lecon', 'questions']));
    }

    public function update(UpdateQuizRequest $request, Quiz $quiz): QuizResource
    {
        $this->authorize('update', $quiz);

        $quiz->update($request->validated());

        return new QuizResource($quiz);
    }

    public function destroy(Quiz $quiz): JsonResponse
    {
        $this->authorize('delete', $quiz);

        $quiz->delete();

        return response()->json(null, 204);
    }
}
