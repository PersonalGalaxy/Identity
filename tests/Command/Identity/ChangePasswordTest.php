<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Command\Identity;

use PersonalGalaxy\Identity\{
    Command\Identity\ChangePassword,
    Entity\Identity\Identity,
    Entity\Identity\Password,
};
use PHPUnit\Framework\TestCase;

class ChangePasswordTest extends TestCase
{
    public function testInterface()
    {
        $command = new ChangePassword(
            $identity = $this->createMock(Identity::class),
            $password = new Password('foobarbaz')
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($password, $command->password());
    }
}
