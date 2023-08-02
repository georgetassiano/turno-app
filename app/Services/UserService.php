<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
     */
    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByField('email', $email)->first();
    }

    /**
     * Register new user
     */
    public function registerNewUser(array $data): User
    {
        $user = null;
        DB::transaction(function () use ($data, &$user) {
            $user = $this->userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $this->accountService->createAccountForUserId($user->id);
            $user->assignRole('user');
        });

        return $user;
    }
}
