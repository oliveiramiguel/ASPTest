<?php

declare(strict_types=1);

namespace ASPTest\UseCase;

use ASPTest\Entity\User;
use ASPTest\Repository\UserRepository;

class UserCreate
{
    public function __construct(private UserRepository $userRepository) {}

    public function create(User $user): User
    {
        return $this->userRepository->save($user);
    }
}
