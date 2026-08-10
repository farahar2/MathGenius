<?php

namespace App\Http\Controllers;

use App\Actions\Auth\RegisterUserAction;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private readonly RegisterUserAction $registerUserAction,
    ) {}

    /**
     * Inscription
     *
     * Crée un nouvel utilisateur et retourne un token d'authentification.
     *
     * @group Authentification
     *
     * @bodyParam name string required Le nom de famille. Example: Dupont
     * @bodyParam prenom string required Le prénom. Example: Jean
     * @bodyParam email string required L'adresse email. Example: jean@example.com
     * @bodyParam password string required Le mot de passe (min 8 caractères). Example: secret1234
     * @bodyParam password_confirmation string required Confirmation du mot de passe. Example: secret1234
     * @bodyParam role string Le rôle (student par défaut). Example: student
     * @bodyParam niveau_id int L'identifiant du niveau. Example: 1
     *
     * @response 201 {
     *   "user": { "id": 1, "name": "Dupont", "prenom": "Jean", "email": "jean@example.com", "role": "student", "is_premium": false, "niveau": null, "created_at": "..." },
     *   "token": "1|abc123..."
     * }
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->registerUserAction->execute($request->validated());

        return response()->json([
            'user'  => UserResource::make($user),
            'token' => $user->createToken('auth-token')->plainTextToken,
        ], 201);
    }

    /**
     * Connexion
     *
     * Authentifie un utilisateur et retourne un token.
     *
     * @group Authentification
     *
     * @bodyParam email string required L'adresse email. Example: jean@example.com
     * @bodyParam password string required Le mot de passe. Example: secret1234
     *
     * @response {
     *   "user": { "id": 1, "name": "Dupont", "prenom": "Jean", "email": "jean@example.com", "role": "student", "is_premium": false, "niveau": null, "created_at": "..." },
     *   "token": "1|abc123..."
     * }
     *
     * @response 422 {
     *   "message": "The provided credentials are incorrect.",
     *   "errors": { "email": ["The provided credentials are incorrect."] }
     * }
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = \App\Models\User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'user'  => UserResource::make($user),
            'token' => $user->createToken('auth-token')->plainTextToken,
        ]);
    }

    /**
     * Déconnexion
     *
     * Révoque le token actuel.
     *
     * @group Authentification
     * @authenticated
     *
     * @response 204
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(null, 204);
    }

    /**
     * Utilisateur connecté
     *
     * Retourne les informations de l'utilisateur authentifié.
     *
     * @group Authentification
     * @authenticated
     *
     * @response {
     *   "user": { "id": 1, "name": "Dupont", "prenom": "Jean", "email": "jean@example.com", "role": "student", "is_premium": false, "niveau": null, "created_at": "..." }
     * }
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => UserResource::make($request->user()->load('niveau')),
        ]);
    }
}
