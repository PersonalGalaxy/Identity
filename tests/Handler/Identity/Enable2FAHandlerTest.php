<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Handler\Identity;

use PersonalGalaxy\Identity\{
    Handler\Identity\Enable2FAHandler,
    Command\Identity\Enable2FA,
    Repository\IdentityRepository,
    Entity\Identity,
    Entity\Identity\Email,
    Entity\Identity\Password,
};
use PHPUnit\Framework\TestCase;

class Enable2FAHandlerTest extends TestCase
{
    public function testInvokation()
    {
        $handle = new Enable2FAHandler(
            $repository = $this->createMock(IdentityRepository::class)
        );
        $command = new Enable2FA(
            new Email('foo@bar.baz')
        );
        $repository
            ->expects($this->once())
            ->method('get')
            ->with($command->email())
            ->willReturn($identity = Identity::create(
                $command->email(),
                new Password('foo')
            ));

        $this->assertNull($handle($command));
        $this->assertTrue($identity->twoFactorAuthenticationEnabled());
    }
}
