<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Entity\Identity;

use PersonalGalaxy\Identity\{
    Entity\Identity\Password,
    Exception\DomainException,
};
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
                if (strlen($a) < 8) {
                    return false;
                }

                return $a !== $b;
            })
            ->then(function(string $string, string $junk): void {
                $password = new Password($string);

                $this->assertTrue($password->verify($string));
                $this->assertFalse($password->verify($junk));
            });
    }

    public function testThrowWhenLessThan8Characters()
    {
        $this->expectException(DomainException::class);

        new Password('foo');
    }
}
