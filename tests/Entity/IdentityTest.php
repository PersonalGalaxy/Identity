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
            $id = $this->createMock(Identity\Identity::class),
            $email = new Email('foo@bar.baz'),
            $password = new Password('foobarbaz')
        );

        $this->assertInstanceOf(Identity::class, $identity);
        $this->assertInstanceOf(ContainsRecordedEventsInterface::class, $identity);
        $this->assertSame($id, $identity->identity());
        $this->assertSame($email, $identity->email());
        $this->assertCount(1, $identity->recordedEvents());
        $event = $identity->recordedEvents()->first();
        $this->assertInstanceOf(IdentityWasCreated::class, $event);
        $this->assertSame($id, $event->identity());
        $this->assertSame($email, $event->email());
    }

    public function testVerifyPassword()
    {
        $this
            ->forAll(Generator\string(), Generator\string())
            ->when(static function(string $a, string $b): bool {
                if (strlen($a) < 8) {
                    return false;
                }

                return $a !== $b;
            })
            ->then(function(string $a, string $b): void {
                $identity = Identity::create(
                    $this->createMock(Identity\Identity::class),
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
                if (strlen($a) < 8) {
                    return false;
                }

                if (strlen($b) < 8) {
                    return false;
                }

                return $a !== $b;
            })
            ->then(function(string $a, string $b): void {
                $identity = Identity::create(
                    $id = $this->createMock(Identity\Identity::class),
                    new Email('foo@bar.baz'),
                    new Password($a)
                );

                $this->assertSame($identity, $identity->changePassword(new Password($b)));
                $this->assertCount(2, $identity->recordedEvents());
                $event = $identity->recordedEvents()->last();
                $this->assertInstanceOf(PasswordWasChanged::class, $event);
                $this->assertSame($id, $event->identity());

                $this->assertTrue($identity->verify($b));
                $this->assertFalse($identity->verify($a));
            });
    }

    public function testDelete()
    {
        $identity = Identity::create(
            $id = $this->createMock(Identity\Identity::class),
            new Email('foo@bar.baz'),
            new Password('foobarbaz')
        );

        $this->assertNull($identity->delete());
        $this->assertCount(2, $identity->recordedEvents());
        $event = $identity->recordedEvents()->last();
        $this->assertInstanceOf(IdentityWasDeleted::class, $event);
        $this->assertSame($id, $event->identity());
    }

    public function test2FA()
    {
        $identity = Identity::create(
            $id = $this->createMock(Identity\Identity::class),
            new Email('foo@bar.baz'),
            new Password('foobarbaz')
        );

        $this->assertFalse($identity->twoFactorAuthenticationEnabled());
        $this->assertSame($identity, $identity->enableTwoFactorAuthentication());
        $this->assertTrue($identity->twoFactorAuthenticationEnabled());
        $this->assertCount(2, $identity->recordedEvents());
        $event = $identity->recordedEvents()->last();
        $this->assertInstanceOf(TwoFactorAuthenticationWasEnabled::class, $event);
        $this->assertSame($id, $event->identity());
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
        $this->assertSame($id, $event->identity());

        $this->expectException(LogicException::class);

        $identity->validate(new Code('foo'), $otp);
    }

    public function testValidateTwoFactorCodeViaRecoveryCodes()
    {
        $identity = Identity::create(
            $id = $this->createMock(Identity\Identity::class),
            new Email('foo@bar.baz'),
            new Password('foobarbaz')
        );
        $identity->enableTwoFactorAuthentication();
        $recoveryCodes = $identity->recordedEvents()->last()->recoveryCodes();
        $code = new Code((string) $recoveryCodes->current());

        $this->assertTrue($identity->validate($code));
        $this->assertCount(3, $identity->recordedEvents());
        $event = $identity->recordedEvents()->last();
        $this->assertInstanceOf(RecoveryCodeWasUsed::class, $event);
        $this->assertSame($id, $event->identity());

        //assert a recovery code can be used only once
        $this->assertFalse($identity->validate($code));
        $recoveryCodes->next();
        $this->assertTrue($identity->validate(
            new Code((string) $recoveryCodes->current())
        ));
        $this->assertCount(4, $identity->recordedEvents());
        $event = $identity->recordedEvents()->last();
        $this->assertInstanceOf(RecoveryCodeWasUsed::class, $event);
        $this->assertSame($id, $event->identity());
    }
}
