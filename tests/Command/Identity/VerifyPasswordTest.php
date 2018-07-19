<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Command\Identity;

use PersonalGalaxy\Identity\{
    Command\Identity\VerifyPassword,
    Entity\Identity\Identity,
};
use PHPUnit\Framework\TestCase;

class VerifyPasswordTest extends TestCase
{
    public function testInterface()
    {
        $command = new VerifyPassword(
            $identity = $this->createMock(Identity::class),
            $password = 'foo'
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($password, $command->password());
    }
}
