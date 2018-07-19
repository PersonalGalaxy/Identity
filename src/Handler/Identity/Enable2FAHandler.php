<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Handler\Identity;

use PersonalGalaxy\Identity\{
    Command\Identity\Enable2FA,
    Repository\IdentityRepository,
};

final class Enable2FAHandler
{
    private $repository;

    public function __construct(IdentityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Enable2FA $wished): void
    {
        $this
            ->repository
            ->get($wished->identity())
            ->enableTwoFactorAuthentication();
    }
}
