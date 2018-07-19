<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Handler;

use PersonalGalaxy\Identity\{
    Command\DeleteIdentity,
    Repository\IdentityRepository,
};

final class DeleteIdentityHandler
{
    private $repository;

    public function __construct(IdentityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DeleteIdentity $wished): void
    {
        $identity = $this->repository->get($wished->identity());
        $identity->delete();
        $this->repository->remove($identity->identity());
    }
}
