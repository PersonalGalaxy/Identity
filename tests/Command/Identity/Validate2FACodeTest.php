<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Command\Identity;

use PersonalGalaxy\Identity\{
    Command\Identity\Validate2FACode,
    Entity\Identity\Email,
    TwoFactorAuthentication\Code,
};
use PHPUnit\Framework\TestCase;

class Validate2FACodeTest extends TestCase
{
    public function testInterface()
    {
        $command = new Validate2FACode(
            $email = new Email('foo@bar.baz'),
            $code = new Code('foo')
        );

        $this->assertSame($email, $command->email());
        $this->assertSame($code, $command->code());
    }
}
