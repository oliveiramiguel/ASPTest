<?php

declare(strict_types=1);

namespace ASPTest\Repository;

use ASPTest\Entity\User;
use PDO;

class UserRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findById(int $userId): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        $user = new User(
            id: (int) $result['id'],
            name: $result['name'],
            lastName: $result['last_name'],
            email: $result['email'],
            age: (int) $result['age'],
        );

        return $user;
    }

    public function save(User $user): User
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (name, last_name, email, age)
            VALUES(:name, :last_name, :email, :age)'
        );

        $stmt->execute([
            ':name' => $user->getName(),
            ':last_name' => $user->getLastName(),
            ':email' => $user->getEmail(),
            ':age' => $user->getAge()
        ]);

        $user->setId((int) $this->pdo->lastInsertId());

        return $user;
    }

    public function savePassword(User $user, string $password): void
    {
        $stmt = $this->pdo->prepare('UPDATE users SET password = :password WHERE id = :id');

        $stmt->execute([
            ':password' => $password,
            ':id' => $user->getId(),
        ]);
    }

    public function findPassword(User $user): ?String
    {
        $stmt = $this->pdo->prepare('SELECT password FROM users WHERE id = ?');
        $stmt->execute([$user->getId()]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['password'];
    }
}
