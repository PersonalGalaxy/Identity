<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Command\Identity;

use PersonalGalaxy\Identity\{
    Command\Identity\Enable2FA,
    Entity\Identity\Identity,
};
use PHPUnit\Framework\TestCase;

class Enable2FATest extends TestCase
{
    public function testInterface()
    {
        $command = new Enable2FA(
            $identity = $this->createMock(Identity::class)
        );

        $this->assertSame($identity, $command->identity());
    }
}
