<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Event;

use PersonalGalaxy\Identity\{
    Event\IdentityWasCreated,
    Entity\Identity\Identity,
    Entity\Identity\Email,
};
use PHPUnit\Framework\TestCase;

class IdentityWasCreatedTest extends TestCase
{
    public function testInterface()
    {
        $event = new IdentityWasCreated(
            $identity = $this->createMock(Identity::class),
            $email = new Email('foo@bar.baz')
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($email, $event->email());
    }
}
