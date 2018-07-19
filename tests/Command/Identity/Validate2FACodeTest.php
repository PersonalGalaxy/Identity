<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Command\Identity;

use PersonalGalaxy\Identity\{
    Command\Identity\Validate2FACode,
    Entity\Identity\Identity,
    TwoFactorAuthentication\Code,
};
use PHPUnit\Framework\TestCase;

class Validate2FACodeTest extends TestCase
{
    public function testInterface()
    {
        $command = new Validate2FACode(
            $identity = $this->createMock(Identity::class),
            $code = new Code('foo')
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($code, $command->code());
    }
}
