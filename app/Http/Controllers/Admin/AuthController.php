<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Repositories\AdminRepository;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function login(LoginRequest $request) : JsonResponse | ValidationException
    {
        $data = $request->validated();
        $user = $this->adminRepository->findByField('email', $data['email'])->first();
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json($user->createToken($data['device_name'], ['guard-admin'])->plainTextToken);
    }

    public function logout(Request $request) : JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json("Usuário deslogado com sucesso");
    }

}
