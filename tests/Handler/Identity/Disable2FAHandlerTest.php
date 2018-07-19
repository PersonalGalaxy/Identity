<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Handler\Identity;

use PersonalGalaxy\Identity\{
    Handler\Identity\Disable2FAHandler,
    Command\Identity\Disable2FA,
    Repository\IdentityRepository,
    Entity\Identity,
    Entity\Identity\Email,
    Entity\Identity\Password,
};
use PHPUnit\Framework\TestCase;

class Disable2FAHandlerTest extends TestCase
{
    public function testInvokation()
    {
        $handle = new Disable2FAHandler(
            $repository = $this->createMock(IdentityRepository::class)
        );
        $command = new Disable2FA(
            $this->createMock(Identity\Identity::class)
        );
        $repository
            ->expects($this->once())
            ->method('get')
            ->with($command->identity())
            ->willReturn($identity = Identity::create(
                $command->identity(),
                new Email('foo@bar.baz'),
                new Password('foo')
            ));

        $identity->enableTwoFactorAuthentication();
        $this->assertNull($handle($command));
        $this->assertFalse($identity->twoFactorAuthenticationEnabled());
    }
}
