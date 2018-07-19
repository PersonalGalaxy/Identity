<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Command;

use PersonalGalaxy\Identity\{
    Command\CreateIdentity,
    Entity\Identity\Identity,
    Entity\Identity\Email,
    Entity\Identity\Password,
};
use PHPUnit\Framework\TestCase;

class CreateIdentityTest extends TestCase
{
    public function testInterface()
    {
        $command = new CreateIdentity(
            $identity = $this->createMock(Identity::class),
            $email = new Email('foo@bar.baz'),
            $password = new Password('foo')
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($email, $command->email());
        $this->assertSame($password, $command->password());
    }
}
