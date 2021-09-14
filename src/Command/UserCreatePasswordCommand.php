<?php

declare(strict_types=1);

namespace ASPTest\Command;

use ASPTest\Command\Input\UserCreatePasswordInput;
use ASPTest\UseCase\UserCreatePassword;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserCreatePasswordCommand extends Command
{
    protected static $defaultName = 'USER:CREATE-PWD';

    public function __construct(private UserCreatePassword $userCreatePassword) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('user_id', InputArgument::REQUIRED, 'User ID')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
            ->addArgument('confirm_password', InputArgument::REQUIRED, 'User confirm password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userCreateInput = UserCreatePasswordInput::fromConsole($input);
        $erros = $userCreateInput->validate();
        if ($erros) {
            $this->printErrors($erros, $output);
            return Command::INVALID;
        }

        $this->userCreatePassword->create($userCreateInput->getUserId(), $userCreateInput->getPassword());
        $output->writeln('<info>Password updated successfully</info>');

        return Command::SUCCESS;
    }

    private function printErrors(array $errors, OutputInterface $output)
    {
        foreach ($errors as $error) {
            $output->writeln('<error> - ' . $error . '<error>');
        }
    }
}
