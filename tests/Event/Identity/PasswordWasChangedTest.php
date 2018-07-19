<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Event\Identity;

use PersonalGalaxy\Identity\{
    Event\Identity\PasswordWasChanged,
    Entity\Identity\Identity,
};
use PHPUnit\Framework\TestCase;

class PasswordWasChangedTest extends TestCase
{
    public function testInterface()
    {
        $event = new PasswordWasChanged(
            $identity = $this->createMock(Identity::class)
        );

        $this->assertSame($identity, $event->identity());
    }
}
