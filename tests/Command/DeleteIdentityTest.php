<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Command;

use PersonalGalaxy\Identity\{
    Command\DeleteIdentity,
    Entity\Identity\Identity,
};
use PHPUnit\Framework\TestCase;

class DeleteIdentityTest extends TestCase
{
    public function testInterface()
    {
        $command = new DeleteIdentity(
            $identity = $this->createMock(Identity::class)
        );

        $this->assertSame($identity, $command->identity());
    }
}
