<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Entity;

use PersonalGalaxy\Identity\{
    Entity\Identity,
    Entity\Identity\Email,
    Entity\Identity\Password,
    Entity\Identity\SecretKey,
    Event\IdentityWasCreated,
    Event\IdentityWasDeleted,
    Event\Identity\PasswordWasChanged,
    Event\Identity\TwoFactorAuthenticationWasEnabled,
    Event\Identity\TwoFactorAuthenticationWasDisabled,
    Event\Identity\RecoveryCodeWasUsed,
    TwoFactorAuthentication\Code,
    Exception\LogicException,
};
use Innmind\EventBus\ContainsRecordedEventsInterface;
use ParagonIE\MultiFactor\OTP\OTPInterface;
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

    public function test2FA()
    {
        $identity = Identity::create(
            $email = new Email('foo@bar.baz'),
            new Password('foo')
        );

        $this->assertFalse($identity->twoFactorAuthenticationEnabled());
        $this->assertSame($identity, $identity->enableTwoFactorAuthentication());
        $this->assertTrue($identity->twoFactorAuthenticationEnabled());
        $this->assertCount(2, $identity->recordedEvents());
        $event = $identity->recordedEvents()->last();
        $this->assertInstanceOf(TwoFactorAuthenticationWasEnabled::class, $event);
        $this->assertSame($email, $event->email());
        $this->assertInstanceOf(SecretKey::class, $event->secretKey());
        $this->assertCount(10, $event->recoveryCodes());

        $otp = $this->createMock(OTPInterface::class);
        $otp
            ->expects($this->exactly(2))
            ->method('getCode')
            ->willReturn('foo');

        $this->assertTrue($identity->validate(new Code('foo'), $otp));
        $this->assertFalse($identity->validate(new Code('bar'), $otp));

        $this->assertSame($identity, $identity->disableTwoFactorAuthentication());
        $this->assertFalse($identity->twoFactorAuthenticationEnabled());
        $this->assertCount(3, $identity->recordedEvents());
        $event = $identity->recordedEvents()->last();
        $this->assertInstanceOf(TwoFactorAuthenticationWasDisabled::class, $event);
        $this->assertSame($email, $event->email());

        $this->expectException(LogicException::class);

        $identity->validate(new Code('foo'), $otp);
    }

    public function testValidateTwoFactorCodeViaRecoveryCodes()
    {
        $identity = Identity::create(
            $email = new Email('foo@bar.baz'),
            new Password('foo')
        );
        $identity->enableTwoFactorAuthentication();
        $recoveryCodes = $identity->recordedEvents()->last()->recoveryCodes();
        $code = new Code((string) $recoveryCodes->current());

        $this->assertTrue($identity->validate($code));
        $this->assertCount(3, $identity->recordedEvents());
        $event = $identity->recordedEvents()->last();
        $this->assertInstanceOf(RecoveryCodeWasUsed::class, $event);
        $this->assertSame($email, $event->email());

        //assert a recovery code can be used only once
        $this->assertFalse($identity->validate($code));
        $recoveryCodes->next();
        $this->assertTrue($identity->validate(
            new Code((string) $recoveryCodes->current())
        ));
        $this->assertCount(4, $identity->recordedEvents());
        $event = $identity->recordedEvents()->last();
        $this->assertInstanceOf(RecoveryCodeWasUsed::class, $event);
        $this->assertSame($email, $event->email());
    }
}
