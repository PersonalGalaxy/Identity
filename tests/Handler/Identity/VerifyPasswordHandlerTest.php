<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\Identity\Handler\Identity;

use PersonalGalaxy\Identity\{
    Handler\Identity\VerifyPasswordHandler,
    Command\Identity\VerifyPassword,
    Repository\IdentityRepository,
    Entity\Identity,
    Entity\Identity\Email,
    Entity\Identity\Password,
    Exception\InvalidPassword,
};
use PHPUnit\Framework\TestCase;

class VerifyPasswordHandlerTest extends TestCase
{
    public function testInvokation()
    {
        $handle = new VerifyPasswordHandler(
            $repository = $this->createMock(IdentityRepository::class)
        );
        $command = new VerifyPassword(
            new Email('foo@bar.baz'),
            'foo'
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
    }

    public function testThrowWhenInvalidPassword()
    {
        $handle = new VerifyPasswordHandler(
            $repository = $this->createMock(IdentityRepository::class)
        );
        $command = new VerifyPassword(
            new Email('foo@bar.baz'),
            'bar'
        );
        $repository
            ->expects($this->once())
            ->method('get')
            ->with($command->email())
            ->willReturn($identity = Identity::create(
                $command->email(),
                new Password('foo')
            ));

        $this->expectException(InvalidPassword::class);

        $handle($command);
    }
}
