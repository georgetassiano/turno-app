<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Repositories\AdminRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\AuthUserResource;

class AuthController extends Controller
{
    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * login admin
     */
    public function login(LoginRequest $request): JsonResponse|ValidationException
    {
        $data = $request->validated();
        $user = $this->adminRepository->findByField('email', $data['email'])->first();
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json(['token' => $user->createToken($data['device_name'], ['guard-admin'])->plainTextToken]);
    }

    /**
     * logout admin
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json('Usuário deslogado com sucesso');
    }

    /**
     * get authenticated user
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json(new AuthUserResource($request->user()));
    }

}
