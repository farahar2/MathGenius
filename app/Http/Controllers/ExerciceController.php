<?php

namespace App\Http\Controllers;

use App\Http\Requests\Exercice\StoreExerciceRequest;
use App\Http\Requests\Exercice\UpdateExerciceRequest;
use App\Http\Resources\ExerciceResource;
use App\Models\Exercice;
use App\Models\Lecon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExerciceController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $exercices = Exercice::query()
            ->when($request->has('id_lecon'), fn ($q) => $q->where('id_lecon', $request->integer('id_lecon')))
            ->orderBy('ordre')
            ->get();

        return ExerciceResource::collection($exercices);
    }

    public function indexByLecon(Lecon $lecon): AnonymousResourceCollection
    {
        $exercices = Exercice::where('id_lecon', $lecon->id)
            ->orderBy('ordre')
            ->get();

        return ExerciceResource::collection($exercices);
    }

    public function store(StoreExerciceRequest $request): JsonResponse
    {
        $this->authorize('create', Exercice::class);

        $data = $request->validate([
            'titre' => ['required', 'string'],
            'enonce' => ['required', 'string'],
            'correction' => ['required', 'string'],
            'image' => ['nullable', 'string'],
            'fichier_pdf' => ['nullable', 'string'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
            'id_lecon' => ['required', 'exists:lecons,id'],
        ]);

        return (new ExerciceResource($exercice))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Exercice $exercice): ExerciceResource
    {
        return new ExerciceResource($exercice->load('lecon'));
    }

    public function update(UpdateExerciceRequest $request, Exercice $exercice): ExerciceResource
    {
        $this->authorize('update', $exercice);

        $data = $request->validate([
            'titre' => ['sometimes', 'string'],
            'enonce' => ['sometimes', 'string'],
            'correction' => ['sometimes', 'string'],
            'image' => ['nullable', 'string'],
            'fichier_pdf' => ['nullable', 'string'],
            'ordre' => ['sometimes', 'integer', 'min:0'],
            'is_published' => ['sometimes', 'boolean'],
            'id_lecon' => ['sometimes', 'exists:lecons,id'],
        ]);

        $this->authorize('update', $exercice);

        $exercice->update($request->validated());

        return new ExerciceResource($exercice);
    }

    public function destroy(Exercice $exercice): JsonResponse
    {
        $this->authorize('delete', $exercice);

        $exercice->delete();

        return response()->json(null, 204);
    }
}
