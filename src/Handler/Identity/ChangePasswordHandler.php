<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Handler\Identity;

use PersonalGalaxy\Identity\{
    Command\Identity\ChangePassword,
    Repository\IdentityRepository,
};

final class ChangePasswordHandler
{
    private $repository;

    public function __construct(IdentityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ChangePassword $wished): void
    {
        $this
            ->repository
            ->get($wished->email())
            ->changePassword($wished->password());
    }
}
