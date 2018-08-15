<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Entity\Identity;

use PersonalGalaxy\Identity\Entity\Identity\SecretKey;
use PHPUnit\Framework\TestCase;

class SecretKeyTest extends TestCase
{
    public function testInterface()
    {
        $secret = new SecretKey;

        $this->assertSame(40, strlen((string) $secret));
    }
}
