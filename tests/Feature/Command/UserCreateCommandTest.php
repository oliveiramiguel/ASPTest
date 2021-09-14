<?php

declare(strict_types=1);

namespace Tests\Feature\Command;

use ASPTest\ApplicationLoader;
use PDO;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class UserCreateCommandTest extends TestCase
{
    private PDO $connection;
    private Application $application;
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $this->application = ApplicationLoader::boot();
        $this->connection = ApplicationLoader::getConnection();

        $command = $this->application->find('USER:CREATE');
        $this->commandTester = new CommandTester($command);

        $this->connection->beginTransaction();
    }
    public function testInvalidInput()
    {
        $this->commandTester->execute([
            'name' => 'a',
            'last_name' => 'b',
            'email' => 'c',
            'age' => 'd',
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('The name must be between', $output);
        $this->assertStringContainsString('The last_name must be between', $output);
        $this->assertStringContainsString('The email must be', $output);
        $this->assertStringContainsString('The age must be', $output);
    }

    public function testSuccessCreateUser()
    {
        $this->commandTester->execute([
            'name' => 'Miguel',
            'last_name' => 'Oliveira',
            'email' => 'miguel@test.com',
            'age' => 29,
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('"name": "Miguel"', $output);
        $this->assertStringContainsString('"last_name": "Oliveira"', $output);
        $this->assertStringContainsString('"email": "miguel@test.com"', $output);
        $this->assertStringContainsString('"age": 29', $output);
    }

    protected function tearDown(): void
    {
        $this->connection->rollBack();
    }
}
