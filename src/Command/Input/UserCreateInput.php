<?php

declare(strict_types=1);

namespace ASPTest\Command\Input;

use ASPTest\Entity\User;
use Symfony\Component\Console\Input\InputInterface;

class UserCreateInput
{
    public function __construct(
        private $name,
        private $lastName,
        private $email,
        private $age
    ) {}

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

    public function validate(): array
    {
        $errors = [];

        $nameLen = mb_strlen($this->name);
        if ($nameLen < 2 || $nameLen > 35) {
            $errors['name'] = 'The name must be between 2 and 35 characters.';
        }

        $lastNameLen = mb_strlen($this->lastName);
        if ($lastNameLen < 2 || $lastNameLen > 35) {
            $errors['last_name'] = 'The last_name must be between 2 and 35 characters.';
        }

        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'The email must be a valid email address.';
        }

        if (!is_null($this->age) && ($this->age <= 0 || $this->age > 150)) {
            $errors['age'] = 'The age must be positive number and may not be greater than 150.';
        }

        return $errors;
    }

    public static function fromConsole(InputInterface $input): self
    {
        return new self(
            $input->getArgument('name'),
            $input->getArgument('last_name'),
            $input->getArgument('email'),
            $input->getArgument('age')
        );
    }

    public function toEntity(): User
    {
        return new User(
            id: null,
            name: $this->name,
            lastName: $this->lastName,
            email: $this->email,
            age: $this->age ? (int) $this->age : null,
        );
    }
}
