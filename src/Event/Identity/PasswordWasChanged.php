<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Event\Identity;

use PersonalGalaxy\Identity\Entity\Identity\Identity;

final class PasswordWasChanged
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
