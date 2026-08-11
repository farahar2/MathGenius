<?php

namespace App\Http\Controllers;

use App\Http\Requests\Niveau\StoreNiveauRequest;
use App\Http\Requests\Niveau\UpdateNiveauRequest;
use App\Http\Resources\NiveauResource;
use App\Models\Niveau;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NiveauController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $niveaux = Niveau::query()
            ->orderBy('ordre')
            ->get();

        return NiveauResource::collection($niveaux);
    }

    public function store(StoreNiveauRequest $request): NiveauResource
    {
<<<<<<< Updated upstream
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'ordre' => ['nullable', 'integer', 'min:0'],
        ]);
=======
        $this->authorize('create', Niveau::class);

        $niveau = Niveau::create($request->validated());
>>>>>>> Stashed changes

        return new NiveauResource($niveau);
    }

    public function show(Niveau $niveau): NiveauResource
    {
        return new NiveauResource($niveau->load('chapitres'));
    }

    public function update(UpdateNiveauRequest $request, Niveau $niveau): NiveauResource
    {
<<<<<<< Updated upstream
        $data = $request->validate([
            'nom' => ['sometimes', 'string', 'max:100'],
            'ordre' => ['sometimes', 'integer', 'min:0'],
        ]);
=======
        $this->authorize('update', $niveau);

        $niveau->update($request->validated());
>>>>>>> Stashed changes

        return new NiveauResource($niveau);
    }

    public function destroy(Niveau $niveau): JsonResponse
    {
        $niveau->delete();

        return response()->json(null, 204);
    }
}
