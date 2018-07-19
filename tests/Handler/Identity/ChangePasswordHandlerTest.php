<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Handler\Identity;

use PersonalGalaxy\Identity\{
    Handler\Identity\ChangePasswordHandler,
    Command\Identity\ChangePassword,
    Repository\IdentityRepository,
    Entity\Identity,
    Entity\Identity\Email,
    Entity\Identity\Password,
};
use PHPUnit\Framework\TestCase;

class ChangePasswordHandlerTest extends TestCase
{
    public function testInvokation()
    {
        $handle = new ChangePasswordHandler(
            $repository = $this->createMock(IdentityRepository::class)
        );
        $command = new ChangePassword(
            $this->createMock(Identity\Identity::class),
            new Password('bar')
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

        $this->assertNull($handle($command));
        $this->assertTrue($identity->verify('bar'));
    }
}
