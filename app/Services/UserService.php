<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\AccountServiceInterface;

class UserService extends BaseService implements UserServiceInterface
{

    private UserRepository $userRepository;
    private AccountServiceInterface $accountService;

    public function __construct(UserRepository $userRepository, AccountServiceInterface $accountService)
    {
        $this->userRepository = $userRepository;
        $this->accountService = $accountService;
    }

    /**
     * Find user by email
     * @param string $email
     * @return User|null
     */
    public function findUserByEmail(string $email) : User | null
    {
        return $this->userRepository->findByField('email', $email)->first();
    }

    /**
     * Register new user
     * @param array $data
     * @return User
     */
    public function registerNewUser(array $data) : User
    {
        $user = null;
        DB::transaction(function () use ($data, &$user){
            $user = $this->userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'])
            ]);
            $this->accountService->createAccountForUser($user);
        });
        return $user;
    }
}
