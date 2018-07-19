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
    Specification\Identity\Email as Spec,
    Exception\IdentityAlreadyExist,
};
use Innmind\Immutable\{
    SetInterface,
    Set,
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
            $this->createMock(Identity\Identity::class),
            new Email('foo@bar.baz'),
            new Password('foobarbaz')
        );
        $repository
            ->expects($this->once())
            ->method('matching')
            ->with(new Spec($command->email()))
            ->willReturn(Set::of(Identity::class));
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(static function(Identity $identity) use ($command): bool {
                return $identity->identity() === $command->identity() &&
                    $identity->email() === $command->email() &&
                    $identity->verify('foobarbaz') &&
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
            $this->createMock(Identity\Identity::class),
            new Email('foo@bar.baz'),
            new Password('foobarbaz')
        );
        $repository
            ->expects($this->once())
            ->method('matching')
            ->with(new Spec($command->email()))
            ->willReturn($set = $this->createMock(SetInterface::class));
        $set
            ->expects($this->once())
            ->method('size')
            ->willReturn(1);
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
