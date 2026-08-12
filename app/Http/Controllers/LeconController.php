<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lecon\StoreLeconRequest;
use App\Http\Requests\Lecon\UpdateLeconRequest;
use App\Http\Resources\LeconResource;
use App\Models\Chapitre;
use App\Models\Lecon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LeconController extends Controller
{
    /**
     * Liste des leçons (filtrable par chapitre)
     *
     * @group Leçons
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Lecon::class);

        $lecons = Lecon::query()
            ->visibleTo($request->user())
            ->when($request->has('id_chapitre'), fn ($q) => $q->where('id_chapitre', $request->integer('id_chapitre')))
            ->with('chapitre')
            ->orderBy('ordre')
            ->get();

        return LeconResource::collection($lecons);
    }

    /**
     * Liste des leçons d'un chapitre
     *
     * @group Leçons
     */
    public function indexByChapitre(Request $request, Chapitre $chapitre): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Lecon::class);

        $lecons = $chapitre->lecons()
            ->visibleTo($request->user())
            ->orderBy('ordre')
            ->get();

        return LeconResource::collection($lecons);
    }

    /**
     * Créer une leçon
     *
     * @group Leçons
     */
    public function store(StoreLeconRequest $request): LeconResource
    {
        $this->authorize('create', Lecon::class);

        $lecon = Lecon::create($request->validated());

        return new LeconResource($lecon);
    }

    /**
     * Afficher une leçon
     *
     * @group Leçons
     */
    public function show(Request $request, Lecon $lecon): LeconResource
    {
        $this->authorize('view', $lecon);

        abort_unless($lecon->isVisibleTo($request->user()), 404);

        return new LeconResource($lecon);
    }

    /**
     * Mettre à jour une leçon
     *
     * @group Leçons
     */
    public function update(UpdateLeconRequest $request, Lecon $lecon): LeconResource
    {
        $this->authorize('update', $lecon);

        $lecon->update($request->validated());

        return new LeconResource($lecon);
    }

    /**
     * Supprimer une leçon
     *
     * @group Leçons
     */
    public function destroy(Lecon $lecon): JsonResponse
    {
        $this->authorize('delete', $lecon);

        $lecon->delete();

        return response()->json(null, 204);
    }
}
