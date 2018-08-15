<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Handler\Identity;

use PersonalGalaxy\Identity\{
    Command\Identity\Validate2FACode,
    Repository\IdentityRepository,
    Exception\Invalid2FACode,
};
use Innmind\TimeContinuum\TimeContinuumInterface;

final class Validate2FACodeHandler
{
    private $repository;
    private $clock;

    public function __construct(
        IdentityRepository $repository,
        TimeContinuumInterface $clock
    ) {
        $this->repository = $repository;
        $this->clock = $clock;
    }

    public function __invoke(Validate2FACode $wished): void
    {
        $identity = $this->repository->get($wished->identity());

        if (!$identity->validate($wished->code(), $this->clock)) {
            throw new Invalid2FACode($wished->code());
        }
    }
}
