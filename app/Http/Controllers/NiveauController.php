<?php

namespace App\Http\Controllers;

use App\Models\Niveau;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NiveauController extends Controller
{
    public function index(Request $request)
    {
        return Niveau::query()
            ->orderBy('ordre')
            ->get();
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'ordre' => ['nullable', 'integer', 'min:0'],
        ]);

        return response()->json(Niveau::create($data), 201);
    }

    public function show(Niveau $niveau)
    {
        return $niveau->load('chapitres');
    }

    public function update(Request $request, Niveau $niveau): JsonResponse
    {
        $data = $request->validate([
            'nom' => ['sometimes', 'string', 'max:100'],
            'ordre' => ['sometimes', 'integer', 'min:0'],
        ]);

        $niveau->update($data);

        return response()->json($niveau);
    }

    public function destroy(Niveau $niveau): JsonResponse
    {
        $niveau->delete();

        return response()->json(null, 204);
    }
}