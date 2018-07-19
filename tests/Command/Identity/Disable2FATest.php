<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Command\Identity;

use PersonalGalaxy\Identity\{
    Command\Identity\Disable2FA,
    Entity\Identity\Identity,
};
use PHPUnit\Framework\TestCase;

class Disable2FATest extends TestCase
{
    public function testInterface()
    {
        $command = new Disable2FA(
            $identity = $this->createMock(Identity::class)
        );

        $this->assertSame($identity, $command->identity());
    }
}
