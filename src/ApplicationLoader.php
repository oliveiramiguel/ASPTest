<?php

declare(strict_types=1);

namespace ASPTest;

use ASPTest\Command\UserCreateCommand;
use ASPTest\Command\UserCreatePasswordCommand;
use ASPTest\Repository\UserRepository;
use ASPTest\UseCase\UserCreate;
use ASPTest\UseCase\UserCreatePassword;
use Dotenv\Dotenv;
use PDO;
use Symfony\Component\Console\Application as SymfonyApp;

class ApplicationLoader
{
    private static ?SymfonyApp $application = null;
    private static ?PDO $connection = null;

    public static function boot(): SymfonyApp
    {
        if (self::$application) {
            return self::$application;
        }

        $env = $_ENV['APP_ENV'] ?? 'dev';
        $envFile = '.env';
        if ($env == 'test') {
            $envFile = '.env.test';
        }

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../', $envFile);
        $dotenv->load();

        $dbHost = $_ENV['DB_HOST'];
        $dbName = $_ENV['DB_NAME'];

        self::$connection = new PDO("mysql:host={$dbHost};dbname={$dbName}", 'root', 'root');
        self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $application = new SymfonyApp();
        $userCreate = new UserCreate(new UserRepository(self::$connection));
        $application->add(new UserCreateCommand($userCreate));

        $userCreatePassword = new UserCreatePassword(new UserRepository(self::$connection));
        $application->add(new UserCreatePasswordCommand($userCreatePassword));

        self::$application = $application;

        return self::$application;
    }

    public static function getConnection(): ?PDO
    {
        return self::$connection;
    }
}
