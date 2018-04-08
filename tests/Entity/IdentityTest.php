<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Entity;

use PersonalGalaxy\Identity\{
    Entity\Identity,
    Entity\Identity\Email,
    Entity\Identity\Password,
    Event\IdentityWasCreated,
    Event\IdentityWasDeleted,
    Event\Identity\PasswordWasChanged,
};
use Innmind\EventBus\ContainsRecordedEventsInterface;
use PHPUnit\Framework\TestCase;
use Eris\{
    Generator,
    TestTrait,
};

class IdentityTest extends TestCase
{
    use TestTrait;

    public function testCreate()
    {
        $identity = Identity::create(
            $email = new Email('foo@bar.baz'),
            $password = new Password('foo')
        );

        $this->assertInstanceOf(Identity::class, $identity);
        $this->assertInstanceOf(ContainsRecordedEventsInterface::class, $identity);
        $this->assertSame($email, $identity->email());
        $this->assertCount(1, $identity->recordedEvents());
        $event = $identity->recordedEvents()->first();
        $this->assertInstanceOf(IdentityWasCreated::class, $event);
        $this->assertSame($email, $event->email());
    }

    public function testVerifyPassword()
    {
        $this
            ->forAll(Generator\string(), Generator\string())
            ->when(static function(string $a, string $b): bool {
                return $a !== $b;
            })
            ->then(function(string $a, string $b): void {
                $identity = Identity::create(
                    new Email('foo@bar.baz'),
                    new Password($a)
                );

                $this->assertTrue($identity->verify($a));
                $this->assertFalse($identity->verify($b));
            });
    }

    public function testChangePassword()
    {
        $this
            ->forAll(Generator\string(), Generator\string())
            ->when(static function(string $a, string $b): bool {
                return $a !== $b;
            })
            ->then(function(string $a, string $b): void {
                $identity = Identity::create(
                    $email = new Email('foo@bar.baz'),
                    new Password($a)
                );

                $this->assertSame($identity, $identity->changePassword(new Password($b)));
                $this->assertCount(2, $identity->recordedEvents());
                $event = $identity->recordedEvents()->last();
                $this->assertInstanceOf(PasswordWasChanged::class, $event);
                $this->assertSame($email, $event->email());

                $this->assertTrue($identity->verify($b));
                $this->assertFalse($identity->verify($a));
            });
    }

    public function testDelete()
    {
        $identity = Identity::create(
            $email = new Email('foo@bar.baz'),
            new Password('foo')
        );

        $this->assertNull($identity->delete());
        $this->assertCount(2, $identity->recordedEvents());
        $event = $identity->recordedEvents()->last();
        $this->assertInstanceOf(IdentityWasDeleted::class, $event);
        $this->assertSame($email, $event->email());
    }
}
