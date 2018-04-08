<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Event\Identity;

use PersonalGalaxy\Identity\{
    Event\Identity\TwoFactorAuthenticationWasDisabled,
    Entity\Identity\Email,
};
use PHPUnit\Framework\TestCase;

class TwoFactorAuthenticationWasDisabledTest extends TestCase
{
    public function testInterface()
    {
        $event = new TwoFactorAuthenticationWasDisabled(
            $email = new Email('foo@bar.baz')
        );

        $this->assertSame($email, $event->email());
    }
}
