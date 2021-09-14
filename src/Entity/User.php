<?php

declare(strict_types=1);

namespace ASPTest\Entity;

use JsonSerializable;

class User implements JsonSerializable
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $lastName,
        private string $email,
        private ?int $age
    ) {}

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'age' => $this->age
        ];
    }
}