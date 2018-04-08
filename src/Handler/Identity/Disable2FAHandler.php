<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Handler\Identity;

use PersonalGalaxy\Identity\{
    Command\Identity\Disable2FA,
    Repository\IdentityRepository,
};

final class Disable2FAHandler
{
    private $repository;

    public function __construct(IdentityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Disable2FA $wished): void
    {
        $this
            ->repository
            ->get($wished->email())
            ->disableTwoFactorAuthentication();
    }
}
