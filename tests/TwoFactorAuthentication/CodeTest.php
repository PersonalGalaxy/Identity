<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\TwoFactorAuthentication;

use PersonalGalaxy\Identity\{
    TwoFactorAuthentication\Code,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;
use Eris\{
    Generator,
    TestTrait,
};

class CodeTest extends TestCase
{
    use TestTrait;

    public function testInterface()
    {
        $this
            ->forAll(Generator\string())
            ->when(static function(string $string): bool {
                return $string !== '';
            })
            ->then(function(string $string): void {
                $this->assertSame($string, (string) new Code($string));
            });
    }

    public function testThrowWhenEmptyCode()
    {
        $this->expectException(DomainException::class);

        new Code('');
    }
}
