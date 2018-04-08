<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Command\Identity;

use PersonalGalaxy\Identity\{
    Command\Identity\ChangePassword,
    Entity\Identity\Email,
    Entity\Identity\Password,
};
use PHPUnit\Framework\TestCase;

class ChangePasswordTest extends TestCase
{
    public function testInterface()
    {
        $command = new ChangePassword(
            $email = new Email('foo@bar.baz'),
            $password = new Password('foo')
        );

        $this->assertSame($email, $command->email());
        $this->assertSame($password, $command->password());
    }
}
