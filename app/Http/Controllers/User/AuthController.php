<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\UserAuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\AuthUserResource;

class AuthController extends Controller
{
    private UserAuthServiceInterface $userAuthService;

    public function __construct(UserAuthServiceInterface $userAuthService)
    {
        $this->userAuthService = $userAuthService;
    }

    /**
     * login user
     */
    public function login(LoginRequest $request): JsonResponse|ValidationException
    {
        $data = $request->validated();
        $token = $this->userAuthService->getTokenForCredentials($data);

        return response()->json(['token' => $token]);
    }

    /**
     * logout user
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json('Usuário deslogado com sucesso');
    }

    /**
     * register new user
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $token = $this->userAuthService->registerNewUserAndCreateToken($data);

        return response()->json(['token' => $token], 201);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json(new AuthUserResource($request->user()));
    }
}
