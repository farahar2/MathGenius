<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chapitre\StoreChapitreRequest;
use App\Http\Requests\Chapitre\UpdateChapitreRequest;
use App\Http\Resources\ChapitreResource;
use App\Models\Chapitre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChapitreController extends Controller
{
    /**
     * Liste des chapitres
     *
     * @group Chapitres
     *
     * @response [{"id":1,"titre":"Chapitre 1","description":"...","ordre":1,"is_published":true,"id_niveau":1,"created_at":"...","updated_at":"..."}]
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $chapitres = Chapitre::query()
            ->when($request->has('id_niveau'), fn ($q) => $q->where('id_niveau', $request->integer('id_niveau')))
            ->orderBy('ordre')
            ->get();

        return ChapitreResource::collection($chapitres);
    }

    /**
     * Créer un chapitre
     *
     * @group Chapitres
     *
     * @response 201 [{"id":1,"titre":"Chapitre 1","description":"...","ordre":1,"is_published":true,"id_niveau":1}]
     */
    public function store(StoreChapitreRequest $request): ChapitreResource
    {
        $this->authorize('create', Chapitre::class);

        $chapitre = Chapitre::create($request->validated());

        return new ChapitreResource($chapitre);
    }

    /**
     * Afficher un chapitre
     *
     * @group Chapitres
     *
     * @response [{"id":1,"titre":"Chapitre 1","description":"...","ordre":1,"is_published":true,"id_niveau":1}]
     */
    public function show(Chapitre $chapitre): ChapitreResource
    {
        return new ChapitreResource($chapitre);
    }

    /**
     * Mettre à jour un chapitre
     *
     * @group Chapitres
     *
     * @response [{"id":1,"titre":"Chapitre 1","description":"...","ordre":1,"is_published":true,"id_niveau":1}]
     */
    public function update(UpdateChapitreRequest $request, Chapitre $chapitre): ChapitreResource
    {
        $this->authorize('update', $chapitre);

        $chapitre->update($request->validated());

        return new ChapitreResource($chapitre);
    }

    /**
     * Supprimer un chapitre
     *
     * @group Chapitres
     *
     * @response 204
     */
    public function destroy(Chapitre $chapitre): JsonResponse
    {
        $this->authorize('delete', $chapitre);

        $chapitre->delete();

        return response()->json(null, 204);
    }
}
