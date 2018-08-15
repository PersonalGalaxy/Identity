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
use Innmind\TimeContinuum\TimeContinuumInterface;
use PHPUnit\Framework\TestCase;

class Validate2FACodeHandlerTest extends TestCase
{
    public function testInvokation()
    {
        $handle = new Validate2FACodeHandler(
            $repository = $this->createMock(IdentityRepository::class),
            $this->createMock(TimeContinuumInterface::class)
        );
        $identity = Identity::create(
            $this->createMock(Identity\Identity::class),
            new Email('foo@bar.baz'),
            new Password('foobarbaz')
        );
        $identity->enableTwoFactorAuthentication();
        $command = new Validate2FACode(
            $identity->identity(),
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
            ->with($identity->identity())
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
            $repository = $this->createMock(IdentityRepository::class),
            $this->createMock(TimeContinuumInterface::class)
        );
        $identity = Identity::create(
            $this->createMock(Identity\Identity::class),
            new Email('foo@bar.baz'),
            new Password('foobarbaz')
        );
        $identity->enableTwoFactorAuthentication();
        $command = new Validate2FACode(
            $identity->identity(),
            new Code('foo')
        );
        $repository
            ->expects($this->once())
            ->method('get')
            ->with($identity->identity())
            ->willReturn($identity);

        try {
            $handle($command);
        } catch (Invalid2FACode $e) {
            $this->assertSame($command->code(), $e->code());
        }
    }
}
