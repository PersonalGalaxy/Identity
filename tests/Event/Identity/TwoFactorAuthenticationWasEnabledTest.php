<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Event\Identity;

use PersonalGalaxy\Identity\{
    Event\Identity\TwoFactorAuthenticationWasEnabled,
    Entity\Identity\Identity,
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
            $identity = $this->createMock(Identity::class),
            $secretKey = new SecretKey,
            $recoveryCodes = Set::of(RecoveryCode::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($secretKey, $event->secretKey());
        $this->assertSame($recoveryCodes, $event->recoveryCodes());
    }

    public function testThrowWhenInvalidRecoveryCodeSet()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Argument 3 must be of type SetInterface<PersonalGalaxy\Identity\Entity\Identity\RecoveryCode>');

        $event = new TwoFactorAuthenticationWasEnabled(
            $this->createMock(Identity::class),
            new SecretKey,
            Set::of('string')
        );
    }
}
