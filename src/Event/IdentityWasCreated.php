<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Event;

use PersonalGalaxy\Identity\Entity\Identity\{
    Identity,
    Email,
};

final class IdentityWasCreated
{
    private $identity;
    private $email;

    public function __construct(Identity $identity, Email $email)
    {
        $this->identity = $identity;
        $this->email = $email;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function email(): Email
    {
        return $this->email;
    }
}
