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
        $this->authorize('create', Niveau::class);

        $niveau = Niveau::create($request->validated());

        return new NiveauResource($niveau);
    }

    public function show(Niveau $niveau): NiveauResource
    {
        return new NiveauResource($niveau->load('chapitres'));
    }

    public function update(UpdateNiveauRequest $request, Niveau $niveau): NiveauResource
    {
        $this->authorize('update', $niveau);

        $niveau->update($request->validated());

        return new NiveauResource($niveau);
    }

    public function destroy(Niveau $niveau): JsonResponse
    {
        $this->authorize('delete', $niveau);

        $niveau->delete();

        return response()->json(null, 204);
    }
}
