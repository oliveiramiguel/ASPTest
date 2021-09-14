<?php

declare(strict_types=1);

namespace Tests\Unit\Command\Input;

use ASPTest\Command\Input\UserCreatePasswordInput;
use PHPUnit\Framework\TestCase;

class UserCreatePasswordInputTest extends TestCase
{
    /**
     * @dataProvider invalidDataprovider
     */
    public function testValidateWithInvalidInput(array $data)
    {
        $userCreateInput = new UserCreatePasswordInput(...$data);

        $this->assertNotEmpty($userCreateInput->validate());
    }

    public function invalidDataprovider()
    {
        return [
            'short password' => [
                [
                    'userId' => 1,
                    'password' => 'aA@1',
                    'confirmPassword' => 'aA@1'
                ]
            ],
            'without number' => [
                [
                    'userId' => 1,
                    'password' => 'aA@aaaaa',
                    'confirmPassword' => 'aA@aaaaa'
                ]
            ],
            'without uppercase' => [
                [
                    'userId' => 1,
                    'password' => 'a1@aaaaa',
                    'confirmPassword' => 'a1@aaaaa'
                ]
            ],
            'without special' => [
                [
                    'userId' => 1,
                    'password' => 'a1Aaaaaa',
                    'confirmPassword' => 'a1Aaaaaa'
                ]
            ],
            'password does not match' => [
                [
                    'userId' => 1,
                    'password' => 'aA@1aA@1',
                    'confirmPassword' => 'XXXXXXX'
                ]
            ],

        ];
    }

    public function testValidateWithValidInput()
    {
        $userCreateInput = new UserCreatePasswordInput(1, 'aA@1aA@1', 'aA@1aA@1');

        $this->assertEmpty($userCreateInput->validate());
    }
}
