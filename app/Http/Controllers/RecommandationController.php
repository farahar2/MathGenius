<?php

namespace App\Http\Controllers;

use App\Models\Recommandation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecommandationController extends Controller
{
    public function index(Request $request)
    {
        return Recommandation::query()
            ->when($request->has('id_utilisateur'), fn ($q) => $q->where('id_utilisateur', $request->integer('id_utilisateur')))
            ->when($request->user(), fn ($q) => $q->where('id_utilisateur', $request->user()->id))
            ->orderByDesc('created_at')
            ->get();
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message' => ['nullable', 'string'],
            'id_chapitre' => ['required', 'exists:chapitres,id'],
        ]);

        $data['id_utilisateur'] = $request->user()?->id;

        return response()->json(Recommandation::create($data), 201);
    }

    public function update(Request $request, Recommandation $recommandation): JsonResponse
    {
        $data = $request->validate([
            'message' => ['sometimes', 'string'],
            'is_lue' => ['sometimes', 'boolean'],
        ]);

        $recommandation->update($data);

        return response()->json($recommandation);
    }

    public function destroy(Recommandation $recommandation): JsonResponse
    {
        $recommandation->delete();

        return response()->json(null, 204);
    }
}