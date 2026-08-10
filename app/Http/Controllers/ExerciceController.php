<?php

namespace App\Http\Controllers;

use App\Models\Exercice;
use App\Models\Lecon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExerciceController extends Controller
{
    public function index(Request $request)
    {
        return Exercice::query()
            ->when($request->has('id_lecon'), fn ($q) => $q->where('id_lecon', $request->integer('id_lecon')))
            ->orderBy('ordre')
            ->get();
    }

    public function indexByLecon(Lecon $lecon)
    {
        return Exercice::where('id_lecon', $lecon->id)
            ->orderBy('ordre')
            ->get();
    }

    public function store(Request $request): JsonResponse
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

        return response()->json(Exercice::create($data), 201);
    }

    public function show(Exercice $exercice): JsonResponse
    {
        return response()->json($exercice->load('lecon'));
    }

    public function update(Request $request, Exercice $exercice): JsonResponse
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

        $exercice->update($data);

        return response()->json($exercice);
    }

    public function destroy(Exercice $exercice): JsonResponse
    {
        $this->authorize('delete', $exercice);

        $exercice->delete();

        return response()->json(null, 204);
    }
}