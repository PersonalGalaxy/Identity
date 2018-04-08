<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Entity\Identity;

use PersonalGalaxy\Identity\{
    Entity\Identity\Email,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;
use Eris\{
    Generator,
    TestTrait,
};

class EmailTest extends TestCase
{
    use TestTrait;

    public function testInterface()
    {
        $this->assertSame('foo@bar.baz', (string) new Email('foo@bar.baz'));
    }

    public function testThrowWhenInvalidEmail()
    {
        $this
            ->forAll(Generator\string())
            ->when(static function(string $string): bool {
                return !filter_var($string, FILTER_VALIDATE_EMAIL);
            })
            ->then(function(string $string): void {
                $this->expectException(DomainException::class);

                new Email($string);
            });
    }
}
