<?php

declare(strict_types=1);

namespace ASPTest\UseCase;

use ASPTest\Repository\UserRepository;

class UserCreatePassword
{
    public function __construct(private UserRepository $userRepository) {}

    public function create(int $userId, string $password): void
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new \DomainException('User not found');
        }

        $options = [
            'cost' => 10,
        ];
        $passwordHash = password_hash($password, PASSWORD_BCRYPT, $options);

        $this->userRepository->savePassword($user, $passwordHash);
    }
}
