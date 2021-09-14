<?php

declare(strict_types=1);

namespace Tests\Unit\Command\Input;

use ASPTest\Command\Input\UserCreateInput;
use PHPUnit\Framework\TestCase;

class UserCreateInputTest extends TestCase
{
    /**
     * @dataProvider invalidDataprovider
     */
    public function testValidateWithInvalidInput(array $data)
    {
        $userCreateInput = new UserCreateInput(...$data);

        $this->assertNotEmpty($userCreateInput->validate());
    }

    public function invalidDataprovider()
    {
        return [
            'short name' => [
                [
                    'name' => 'x',
                    'lastName' => 'Oliveira',
                    'email' => 'miguel@test.com',
                    'age' => 29
                ]
            ],
            'short last name' => [
                [
                    'name' => 'Miguel',
                    'lastName' => 'x',
                    'email' => 'miguel@test.com',
                    'age' => 29
                ]
            ],
            'email without at' => [
                [
                    'name' => 'Miguel',
                    'lastName' => 'Oliveira',
                    'email' => 'email-without-at.com',
                    'age' => 29
                ]
            ],
            'zeroed age' => [
                [
                    'name' => 'Miguel',
                    'lastName' => 'Oliveira',
                    'email' => 'email-without-at.com',
                    'age' => 0
                ]
            ],
            'negative age' => [
                [
                    'name' => 'Miguel',
                    'lastName' => 'Oliveira',
                    'email' => 'email-without-at.com',
                    'age' => -1
                ]
            ],
            'max age' => [
                [
                    'name' => 'Miguel',
                    'lastName' => 'Oliveira',
                    'email' => 'email-without-at.com',
                    'age' => 151
                ]
            ]
        ];
    }

    public function testValidateWithValidInput()
    {
        $userCreateInput = new UserCreateInput('Miguel', 'Oliveira', 'miguel@test.com', 29);

        $this->assertEmpty($userCreateInput->validate());
    }

    public function testValidateWithoutAge()
    {
        $userCreateInput = new UserCreateInput('Miguel', 'Oliveira', 'miguel@test.com', null);

        $this->assertEmpty($userCreateInput->validate());
    }

    public function testToEntity()
    {
        $userCreateInput = new UserCreateInput('Miguel', 'Oliveira', 'miguel@test.com', null);
        $user = $userCreateInput->toEntity();

        $this->assertNull($user->getId());
        $this->assertEquals($userCreateInput->getName(), $user->getName());
        $this->assertEquals($userCreateInput->getLastName(), $user->getLastName());
        $this->assertEquals($userCreateInput->getEmail(), $user->getEmail());
        $this->assertEquals($userCreateInput->getAge(), $user->getAge());
    }
}
