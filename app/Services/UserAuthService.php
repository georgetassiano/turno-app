<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;
use App\Services\UserServiceInterface;

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
     * @param string $password
     * @param User|null $user
     * @return bool|ValidationException
     * @throws ValidationException
     */
    public function isValidCredentials(string $password, User $user) : bool | ValidationException
    {
        if (! $user || ! Hash::check($password, $user->password)) {
            $this->throwInvalidCredentialsException();
        }
        return true;
    }

    /**
     * Throw invalid credentials exception
     * @return ValidationException
     * @throws ValidationException
     */
    public function throwInvalidCredentialsException() : ValidationException
    {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    /**
     * Create token for user
     * @param User $user
     * @param string $deviceName
     * @param array $abilities
     * @return string
     */
    public function createToken(User $user, string $deviceName) : string
    {
        return $user->createToken($deviceName, self::ABILITIES)->plainTextToken;
    }

    /**
     * Get token for credentials
     * @param string $email
     * @param string $password
     * @param string $deviceName
     * @return string|ValidationException
     * @throws ValidationException
     */
    public function getTokenForCredentials(array $data) : string | ValidationException
    {
        $user = $this->userService->findUserByEmail($data['email']);
        if ($this->isValidCredentials($data['password'], $user)) {
            return $this->createToken($user, $data['device_name']);
        }
    }

    /**
     * Register new user and create token
     * @param array $data
     * @return string
     */
    public function registerNewUserAndCreateToken(array $data) : string
    {
        $dataUser = Arr::except($data, ['device_name']);
        $user = $this->userService->registerNewUser($dataUser);
        return $this->createToken($user, $data['device_name']);
    }
}
