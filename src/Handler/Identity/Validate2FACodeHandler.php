<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Handler\Identity;

use PersonalGalaxy\Identity\{
    Command\Identity\Validate2FACode,
    Repository\IdentityRepository,
    Exception\Invalid2FACode,
};

final class Validate2FACodeHandler
{
    private $repository;

    public function __construct(IdentityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Validate2FACode $wished): void
    {
        $identity = $this->repository->get($wished->identity());

        if (!$identity->validate($wished->code())) {
            throw new Invalid2FACode($wished->code());
        }
    }
}
