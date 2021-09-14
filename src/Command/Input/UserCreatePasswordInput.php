<?php

declare(strict_types=1);

namespace ASPTest\Command\Input;

use Symfony\Component\Console\Input\InputInterface;

class UserCreatePasswordInput
{
    public function __construct(
        private $userId,
        private $password,
        private $confirmPassword
    ) {}

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function validate(): array
    {
        $errors = [];

        if ($this->password != $this->confirmPassword) {
            $errors['confirm_password'] = 'The password does not match with confirm_password.';
        }

        if(!preg_match("#.*^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $this->password)) {
            $errors['password'] = 'The password must have at least 8 characters in length, an upper case character, a number and a special character.';
        }

        return $errors;
    }

    public static function fromConsole(InputInterface $input): self
    {
        return new self(
            (int) $input->getArgument('user_id'),
            $input->getArgument('password'),
            $input->getArgument('confirm_password'),
        );
    }
}
