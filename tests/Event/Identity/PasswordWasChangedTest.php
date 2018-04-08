<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Event\Identity;

use PersonalGalaxy\Identity\{
    Event\Identity\PasswordWasChanged,
    Entity\Identity\Email,
};
use PHPUnit\Framework\TestCase;

class PasswordWasChangedTest extends TestCase
{
    public function testInterface()
    {
        $event = new PasswordWasChanged(
            $email = new Email('foo@bar.baz')
        );

        $this->assertSame($email, $event->email());
    }
}
