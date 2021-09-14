<?php

declare(strict_types=1);

namespace ASPTest\Command;

use ASPTest\Command\Input\UserCreateInput;
use ASPTest\UseCase\UserCreate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserCreateCommand extends Command
{
    protected static $defaultName = 'USER:CREATE';

    public function __construct(private UserCreate $userCreate) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'User name')
            ->addArgument('last_name', InputArgument::REQUIRED, 'User last name')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('age', InputArgument::OPTIONAL, 'User age');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userCreateInput = UserCreateInput::fromConsole($input);
        $erros = $userCreateInput->validate();
        if ($erros) {
            $this->printErrors($erros, $output);
            return Command::INVALID;
        }

        $user = $this->userCreate->create($userCreateInput->toEntity());
        $output->writeln('<info>'.json_encode($user, JSON_PRETTY_PRINT) . '</info>');

        return Command::SUCCESS;
    }

    private function printErrors(array $errors, OutputInterface $output)
    {
        foreach ($errors as $error) {
            $output->writeln('<error> - ' . $error . '<error>');
        }
    }
}
