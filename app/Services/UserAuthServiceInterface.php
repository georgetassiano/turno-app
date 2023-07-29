<?php

namespace App\Services;
use Illuminate\Validation\ValidationException;
use App\Models\User;

interface UserAuthServiceInterface extends BaseServiceInterface {

    public function getTokenForCredentials(array $data) : string | ValidationException;

    public function createToken(User $user, string $deviceName) : string;

    public function registerNewUserAndCreateToken(array $data) : string;

}
