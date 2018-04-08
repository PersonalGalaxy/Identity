<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Handler;

use PersonalGalaxy\Identity\{
    Command\CreateIdentity,
    Repository\IdentityRepository,
    Entity\Identity,
    Exception\IdentityAlreadyExist,
};

final class CreateIdentityHandler
{
    private $repository;

    public function __construct(IdentityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateIdentity $wished): void
    {
        if ($this->repository->has($wished->email())) {
            throw new IdentityAlreadyExist($wished->email());
        }

        $this->repository->add(
            Identity::create(
                $wished->email(),
                $wished->password()
            )
        );
    }
}
