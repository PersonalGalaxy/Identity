<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Event;

use PersonalGalaxy\Identity\{
    Event\IdentityWasDeleted,
    Entity\Identity\Email,
};
use PHPUnit\Framework\TestCase;

class IdentityWasDeletedTest extends TestCase
{
    public function testInterface()
    {
        $event = new IdentityWasDeleted(
            $email = new Email('foo@bar.baz')
        );

        $this->assertSame($email, $event->email());
    }
}
