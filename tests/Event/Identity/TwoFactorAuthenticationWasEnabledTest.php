<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Event\Identity;

use PersonalGalaxy\Identity\{
    Event\Identity\TwoFactorAuthenticationWasEnabled,
    Entity\Identity\Email,
    Entity\Identity\SecretKey,
    Entity\Identity\RecoveryCode,
};
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class TwoFactorAuthenticationWasEnabledTest extends TestCase
{
    public function testInterface()
    {
        $event = new TwoFactorAuthenticationWasEnabled(
            $email = new Email('foo@bar.baz'),
            $secretKey = new SecretKey,
            $recoveryCodes = Set::of(RecoveryCode::class)
        );

        $this->assertSame($email, $event->email());
        $this->assertSame($secretKey, $event->secretKey());
        $this->assertSame($recoveryCodes, $event->recoveryCodes());
    }

    public function testThrowWhenInvalidRecoveryCodeSet()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Argument 3 must be of type SetInterface<PersonalGalaxy\Identity\Entity\Identity\RecoveryCode>');

        $event = new TwoFactorAuthenticationWasEnabled(
            new Email('foo@bar.baz'),
            new SecretKey,
            Set::of('string')
        );
    }
}
