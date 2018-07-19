<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Event\Identity;

use PersonalGalaxy\Identity\{
    Event\Identity\RecoveryCodeWasUsed,
    Entity\Identity\Identity,
};
use PHPUnit\Framework\TestCase;

class RecoveryCodeWasUsedTest extends TestCase
{
    public function testInterface()
    {
        $event = new RecoveryCodeWasUsed(
            $identity = $this->createMock(Identity::class)
        );

        $this->assertSame($identity, $event->identity());
    }
}
