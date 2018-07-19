<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Command\Identity;

use PersonalGalaxy\Identity\Entity\Identity\Identity;

final class Enable2FA
{
    private $identity;

    public function __construct(Identity $identity)
    {
        $this->identity = $identity;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }
}
