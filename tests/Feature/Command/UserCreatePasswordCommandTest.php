<?php

declare(strict_types=1);

namespace Tests\Feature\Command;

use ASPTest\ApplicationLoader;
use ASPTest\Entity\User;
use ASPTest\Repository\UserRepository;
use PDO;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class UserCreatePasswordCommandTest extends TestCase
{
    private PDO $connection;
    private Application $application;
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $this->application = ApplicationLoader::boot();
        $this->connection = ApplicationLoader::getConnection();

        $command = $this->application->find('USER:CREATE-PWD');
        $this->commandTester = new CommandTester($command);

        $this->connection->beginTransaction();
    }

    public function testInvalidInput()
    {
        $this->commandTester->execute([
            'user_id' => 'a',
            'password' => 'b',
            'confirm_password' => 'c'
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('The password must have at least 8 characters in length,', $output);
    }

    public function testUserNotFound()
    {
        $this->expectException(\DomainException::class);

        $this->commandTester->execute([
            'user_id' => 1,
            'password' => 'aA@1aA@1',
            'confirm_password' => 'aA@1aA@1'
        ]);
    }

    public function testSuccessSavePassword()
    {
        $repository = new UserRepository($this->connection);

        $user = new User(id: null, name: 'Miguel', lastName: 'Oliveira', email: 'miguel@test.com', age: null);
        $user = $repository->save($user);

        $password = $repository->findPassword($user);
        $this->assertNull($password);

        $this->commandTester->execute([
            'user_id' => $user->getId(),
            'password' => 'aA@1aA@1',
            'confirm_password' => 'aA@1aA@1'
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Password updated successfully', $output);

        $password = $repository->findPassword($user);
        $this->assertNotNull($password);
    }

    protected function tearDown(): void
    {
        $this->connection->rollBack();
    }
}
