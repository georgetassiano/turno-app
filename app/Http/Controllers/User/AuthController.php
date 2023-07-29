<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\UserAuthServiceInterface;

class AuthController extends Controller
{
    private UserAuthServiceInterface $userAuthService;

    public function __construct(UserAuthServiceInterface $userAuthService)
    {
        $this->userAuthService = $userAuthService;
    }

    public function login(LoginRequest $request) : JsonResponse | ValidationException
    {
        $data = $request->validated();
        $token = $this->userAuthService->getTokenForCredentials($data);
        return response()->json($token);
    }

    public function logout(Request $request) : JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json("Usuário deslogado com sucesso");
    }

    public function register(RegisterUserRequest $request) : JsonResponse
    {
        $data = $request->validated();
        $token = $this->userAuthService->registerNewUserAndCreateToken($data);
        return response()->json($token, 201);
    }
}
