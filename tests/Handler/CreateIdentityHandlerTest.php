<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Handler;

use PersonalGalaxy\Identity\{
    Handler\CreateIdentityHandler,
    Command\CreateIdentity,
    Repository\IdentityRepository,
    Entity\Identity,
    Entity\Identity\Email,
    Entity\Identity\Password,
    Event\IdentityWasCreated,
    Exception\IdentityAlreadyExist,
};
use PHPUnit\Framework\TestCase;

class CreateIdentityHandlerTest extends TestCase
{
    public function testInvokation()
    {
        $handle = new CreateIdentityHandler(
            $repository = $this->createMock(IdentityRepository::class)
        );
        $command = new CreateIdentity(
            new Email('foo@bar.baz'),
            new Password('foo')
        );
        $repository
            ->expects($this->once())
            ->method('has')
            ->with($command->email())
            ->willReturn(false);
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(static function(Identity $identity) use ($command): bool {
                return $identity->email() === $command->email() &&
                    $identity->verify('foo') &&
                    $identity->recordedEvents()->first() instanceof IdentityWasCreated;
            }));

        $this->assertNull($handle($command));
    }

    public function testThrowWhenEmailAlreadyUsed()
    {
        $handle = new CreateIdentityHandler(
            $repository = $this->createMock(IdentityRepository::class)
        );
        $command = new CreateIdentity(
            new Email('foo@bar.baz'),
            new Password('foo')
        );
        $repository
            ->expects($this->once())
            ->method('has')
            ->with($command->email())
            ->willReturn(true);
        $repository
            ->expects($this->never())
            ->method('add');

        try {
            $handle($command);

            $this->fail('it should throw');
        } catch (IdentityAlreadyExist $e) {
            $this->assertSame($command->email(), $e->email());
        }
    }
}
