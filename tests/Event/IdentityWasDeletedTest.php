<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Event;

use PersonalGalaxy\Identity\{
    Event\IdentityWasDeleted,
    Entity\Identity\Identity,
};
use PHPUnit\Framework\TestCase;

class IdentityWasDeletedTest extends TestCase
{
    public function testInterface()
    {
        $event = new IdentityWasDeleted(
            $identity = $this->createMock(Identity::class)
        );

        $this->assertSame($identity, $event->identity());
    }
}
