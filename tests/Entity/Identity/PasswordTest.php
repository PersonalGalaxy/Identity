<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Entity\Identity;

use PersonalGalaxy\Identity\Entity\Identity\Password;
use PHPUnit\Framework\TestCase;
use Eris\{
    Generator,
    TestTrait,
};

class PasswordTest extends TestCase
{
    use TestTrait;

    public function testInterface()
    {
        $this
            ->forAll(Generator\string(), Generator\string())
            ->when(static function(string $a, string $b): bool {
                return $a !== $b;
            })
            ->then(function(string $string, string $junk): void {
                $password = new Password($string);

                $this->assertTrue($password->verify($string));
                $this->assertFalse($password->verify($junk));
            });
    }
}
