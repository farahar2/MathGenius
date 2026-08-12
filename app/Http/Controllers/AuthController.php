<?php

namespace App\Http\Controllers;

use App\Actions\Auth\RegisterUserAction;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function __construct(
        private readonly RegisterUserAction $registerUserAction,
    ) {}

    /**
     * Ouvre également une session web pour l'utilisateur.
     *
     * Le frontend consomme l'API avec un token Bearer, mais les pages
     * `/app/*` sont protégées par le middleware `auth` : elles ont donc
     * besoin d'une session. Les requêtes purement API (tests, clients
     * tiers) n'ont pas de session — d'où la garde `hasSession()`.
     */
    private function startWebSession(Request $request, User $user): void
    {
        if (! $request->hasSession()) {
            return;
        }

        Auth::guard('web')->login($user);
        $request->session()->regenerate();
    }

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

        $this->startWebSession($request, $user);

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
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $this->startWebSession($request, $user);

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
        // Sur une requête authentifiée par session, currentAccessToken()
        // renvoie un TransientToken qui n'est pas persisté et n'a pas de
        // delete() : seuls les vrais tokens sont révoqués.
        $token = $request->user()->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }

        if ($request->hasSession()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

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

    /**
     * Mettre à jour mon profil
     *
     * @group Authentification
     * @authenticated
     */
    public function updateMe(UpdateProfileRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $request->user()->update($data);

        return response()->json([
            'user' => UserResource::make($request->user()->fresh()->load('niveau')),
        ]);
    }
}
