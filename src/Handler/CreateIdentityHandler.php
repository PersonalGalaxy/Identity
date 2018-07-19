<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Handler;

use PersonalGalaxy\Identity\{
    Command\CreateIdentity,
    Repository\IdentityRepository,
    Entity\Identity,
    Specification\Identity\Email,
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
        $identities = $this->repository->matching(
            new Email($wished->email())
        );

        if ($identities->size() > 0) {
            throw new IdentityAlreadyExist($wished->email());
        }

        $this->repository->add(
            Identity::create(
                $wished->identity(),
                $wished->email(),
                $wished->password()
            )
        );
    }
}
