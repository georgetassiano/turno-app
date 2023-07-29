<?php

namespace App\Services;
use App\Models\User;
interface AccountServiceInterface extends BaseServiceInterface {
    public function createAccountForUser(User $user) : void;
    public function getBalance() : float;
}
