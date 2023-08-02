<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Validation\ValidationException;

interface UserAuthServiceInterface extends BaseServiceInterface
{
    public function getTokenForCredentials(array $data): string|ValidationException;

    public function createToken(User $user, string $deviceName): string;

    public function registerNewUserAndCreateToken(array $data): string;
}
