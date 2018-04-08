<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Command\Identity;

use PersonalGalaxy\Identity\{
    Command\Identity\VerifyPassword,
    Entity\Identity\Email,
};
use PHPUnit\Framework\TestCase;

class VerifyPasswordTest extends TestCase
{
    public function testInterface()
    {
        $command = new VerifyPassword(
            $email = new Email('foo@bar.baz'),
            $password = 'foo'
        );

        $this->assertSame($email, $command->email());
        $this->assertSame($password, $command->password());
    }
}
