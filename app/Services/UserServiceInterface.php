<?php

namespace App\Services;
use App\Models\User;

interface UserServiceInterface extends BaseServiceInterface {
    public function findUserByEmail(string $email) : User | null;
    public function registerNewUser(array $data) : User;
}
