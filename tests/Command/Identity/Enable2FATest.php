<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Command\Identity;

use PersonalGalaxy\Identity\{
    Command\Identity\Enable2FA,
    Entity\Identity\Email,
};
use PHPUnit\Framework\TestCase;

class Enable2FATest extends TestCase
{
    public function testInterface()
    {
        $command = new Enable2FA(
            $email = new Email('foo@bar.baz')
        );

        $this->assertSame($email, $command->email());
    }
}
