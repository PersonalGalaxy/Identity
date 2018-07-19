<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Handler\Identity;

use PersonalGalaxy\Identity\{
    Command\Identity\VerifyPassword,
    Repository\IdentityRepository,
    Exception\InvalidPassword,
};

final class VerifyPasswordHandler
{
    private $repository;

    public function __construct(IdentityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(VerifyPassword $wished): void
    {
        $identity = $this->repository->get($wished->identity());

        if (!$identity->verify($wished->password())) {
            throw new InvalidPassword;
        }
    }
}
