<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Event\Identity;

use PersonalGalaxy\Identity\{
    Event\Identity\TwoFactorAuthenticationWasDisabled,
    Entity\Identity\Identity,
};
use PHPUnit\Framework\TestCase;

class TwoFactorAuthenticationWasDisabledTest extends TestCase
{
    public function testInterface()
    {
        $event = new TwoFactorAuthenticationWasDisabled(
            $identity = $this->createMock(Identity::class)
        );

        $this->assertSame($identity, $event->identity());
    }
}
