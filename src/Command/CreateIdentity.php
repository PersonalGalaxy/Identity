<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Command;

use PersonalGalaxy\Identity\Entity\Identity\{
    Identity,
    Email,
    Password,
};

final class CreateIdentity
{
    private $identity;
    private $email;
    private $password;

    public function __construct(Identity $identity, Email $email, Password $password)
    {
        $this->identity = $identity;
        $this->email = $email;
        $this->password = $password;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): Password
    {
        return $this->password;
    }
}
