<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Command;

use PersonalGalaxy\Identity\{
    Command\DeleteIdentity,
    Entity\Identity\Email,
};
use PHPUnit\Framework\TestCase;

class DeleteIdentityTest extends TestCase
{
    public function testInterface()
    {
        $command = new DeleteIdentity(
            $email = new Email('foo@bar.baz')
        );

        $this->assertSame($email, $command->email());
    }
}
