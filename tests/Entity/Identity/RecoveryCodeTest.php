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

        $this->assertSame(16, strlen((string) $code));
        $this->assertTrue($code->equals(new Code((string) $code)));
        $this->assertFalse($code->equals(new Code('foo')));
    }
}
