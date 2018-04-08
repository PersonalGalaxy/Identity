<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Handler\Identity;

use PersonalGalaxy\Identity\{
    Handler\Identity\Validate2FACodeHandler,
    Command\Identity\Validate2FACode,
    Repository\IdentityRepository,
    Entity\Identity,
    Entity\Identity\Email,
    Entity\Identity\Password,
    Event\Identity\RecoveryCodeWasUsed,
    TwoFactorAuthentication\Code,
    Exception\Invalid2FACode,
};
use PHPUnit\Framework\TestCase;

class Validate2FACodeHandlerTest extends TestCase
{
    public function testInvokation()
    {
        $handle = new Validate2FACodeHandler(
            $repository = $this->createMock(IdentityRepository::class)
        );
        $identity = Identity::create(
            new Email('foo@bar.baz'),
            new Password('foo')
        );
        $identity->enableTwoFactorAuthentication();
        $command = new Validate2FACode(
            $identity->email(),
            new Code(
                (string) $identity
                    ->recordedEvents()
                    ->last()
                    ->recoveryCodes()
                    ->current()
            )
        );
        $repository
            ->expects($this->once())
            ->method('get')
            ->with($identity->email())
            ->willReturn($identity);

        $this->assertNull($handle($command));
        $this->assertInstanceOf(
            RecoveryCodeWasUsed::class,
            $identity->recordedEvents()->last()
        );
    }

    public function testThrowWhenInvalidCode()
    {
        $handle = new Validate2FACodeHandler(
            $repository = $this->createMock(IdentityRepository::class)
        );
        $identity = Identity::create(
            new Email('foo@bar.baz'),
            new Password('foo')
        );
        $identity->enableTwoFactorAuthentication();
        $command = new Validate2FACode(
            $identity->email(),
            new Code('foo')
        );
        $repository
            ->expects($this->once())
            ->method('get')
            ->with($identity->email())
            ->willReturn($identity);

        try {
            $handle($command);
        } catch (Invalid2FACode $e) {
            $this->assertSame($command->code(), $e->code());
        }
    }
}