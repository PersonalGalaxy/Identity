<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Event\Identity;

use PersonalGalaxy\Identity\{
    Event\Identity\TwoFactorAuthenticationWasEnabled,
    Entity\Identity\Email,
};
use PHPUnit\Framework\TestCase;

class TwoFactorAuthenticationWasEnabledTest extends TestCase
{
    public function testInterface()
    {
        $event = new TwoFactorAuthenticationWasEnabled(
            $email = new Email('foo@bar.baz')
        );

        $this->assertSame($email, $event->email());
    }
}
