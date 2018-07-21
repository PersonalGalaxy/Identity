<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Entity\Identity;

use PersonalGalaxy\Identity\{
    Entity\Identity\RecoveryCode,
    TwoFactorAuthentication\Code,
};
use PHPUnit\Framework\TestCase;

class RecoveryCodeTest extends TestCase
{
    public function testInterface()
    {
        $code = new RecoveryCode;

        $this->assertRegExp('~^[a-z0-9]{8}$~', (string) $code);
        $this->assertTrue($code->equals(new Code((string) $code)));
        $this->assertFalse($code->equals(new Code('foo')));
    }
}
