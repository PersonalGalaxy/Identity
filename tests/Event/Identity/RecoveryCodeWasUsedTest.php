<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Event\Identity;

use PersonalGalaxy\Identity\{
    Event\Identity\RecoveryCodeWasUsed,
    Entity\Identity\Email,
};
use PHPUnit\Framework\TestCase;

class RecoveryCodeWasUsedTest extends TestCase
{
    public function testInterface()
    {
        $event = new RecoveryCodeWasUsed(
            $email = new Email('foo@bar.baz')
        );

        $this->assertSame($email, $event->email());
    }
}
