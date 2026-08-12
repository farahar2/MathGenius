<?php

namespace App\Http\Controllers;

use App\Http\Requests\Recommandation\StoreRecommandationRequest;
use App\Http\Requests\Recommandation\UpdateRecommandationRequest;
use App\Http\Resources\RecommandationResource;
use App\Models\Recommandation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RecommandationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $recommandations = Recommandation::query()
            ->when($request->user(), fn ($q) => $q->where('id_utilisateur', $request->user()->id))
            ->orderByDesc('created_at')
            ->get();

        return RecommandationResource::collection($recommandations);
    }

    public function store(StoreRecommandationRequest $request): JsonResponse
    {
        $this->authorize('create', Recommandation::class);

        $data = $request->validated();
        $data['id_utilisateur'] = $request->user()?->id;

        return (new RecommandationResource(Recommandation::create($data)))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateRecommandationRequest $request, Recommandation $recommandation): RecommandationResource
    {
        $this->authorize('update', $recommandation);

        $recommandation->update($request->validated());

        return new RecommandationResource($recommandation);
    }

    public function destroy(Recommandation $recommandation): JsonResponse
    {
        $this->authorize('delete', $recommandation);

        $recommandation->delete();

        return response()->json(null, 204);
    }
}
