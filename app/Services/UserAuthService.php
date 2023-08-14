<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserAuthService extends BaseService implements UserAuthServiceInterface
{
    private const ABILITIES = ['guard-user'];

    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Check if credentials are valid
     *
     * @param  User|null  $user
     * @param  string  $password
     *
     * @throws ValidationException
     */
    public function isValidCredentials(string $password, User $user): bool|ValidationException
    {
        if (! $user || ! Hash::check($password, $user->password)) {
            $this->throwInvalidCredentialsException();
        }

        return true;
    }

    /**
     * Throw invalid credentials exception
     *
     * @throws ValidationException
     */
    public function throwInvalidCredentialsException(): ValidationException
    {
        throw ValidationException::withMessages([
            'password' => ['The provided credentials are incorrect.'],
        ]);
    }

    /**
     * Create token for user
     * @param  User  $user
     * @param  string  $deviceName
     * @return string
     */
    public function createToken(User $user, string $deviceName): string
    {
        return $user->createToken($deviceName, self::ABILITIES)->plainTextToken;
    }

    /**
     * Get token for credentials
     *
     * @param  string  $email
     * @param  string  $password
     * @param  string  $deviceName
     *
     * @throws ValidationException
     */
    public function getTokenForCredentials(array $data): string|ValidationException
    {
        $user = $this->userService->findUserByEmail($data['email']);
        if ($this->isValidCredentials($data['password'], $user)) {
            return $this->createToken($user, $data['device_name']);
        }
    }

    /**
     * Register new user and create token
     * @param  array<string>  $data
     */
    public function registerNewUserAndCreateToken(array $data): string
    {
        $dataUser = Arr::except($data, ['device_name']);
        $user = $this->userService->registerNewUser($dataUser);

        return $this->createToken($user, $data['device_name']);
    }
}
