<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Specification\Identity;

use PersonalGalaxy\Identity\{
    Specification\Identity\Email,
    Entity\Identity\Email as Model,
};
use Innmind\Specification\ComparatorInterface;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testInterface()
    {
        $spec = new Email(new Model('foo@bar.baz'));

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertSame('email', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('foo@bar.baz', $spec->value());
    }
}
