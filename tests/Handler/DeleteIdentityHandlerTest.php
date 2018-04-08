<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Handler;

use PersonalGalaxy\Identity\{
    Handler\DeleteIdentityHandler,
    Command\DeleteIdentity,
    Repository\IdentityRepository,
    Entity\Identity,
    Entity\Identity\Email,
    Entity\Identity\Password,
    Event\IdentityWasDeleted,
};
use PHPUnit\Framework\TestCase;

class DeleteIdentityHandlerTest extends TestCase
{
    public function testInvokation()
    {
        $handle = new DeleteIdentityHandler(
            $repository = $this->createMock(IdentityRepository::class)
        );
        $command = new DeleteIdentity(
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
        $repository
            ->expects($this->once())
            ->method('remove')
            ->with($identity->email());

        $this->assertNull($handle($command));
        $this->assertInstanceOf(
            IdentityWasDeleted::class,
            $identity->recordedEvents()->last()
        );
    }
}
