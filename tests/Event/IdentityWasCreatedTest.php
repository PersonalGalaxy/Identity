<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Event;

use PersonalGalaxy\Identity\{
    Event\IdentityWasCreated,
    Entity\Identity\Email,
};
use PHPUnit\Framework\TestCase;

class IdentityWasCreatedTest extends TestCase
{
    public function testInterface()
    {
        $event = new IdentityWasCreated(
            $email = new Email('foo@bar.baz')
        );

        $this->assertSame($email, $event->email());
    }
}
