<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Command\Identity;

use PersonalGalaxy\Identity\Entity\Identity\{
    Identity,
    Password,
};

final class ChangePassword
{
    private $identity;
    private $password;

    public function __construct(Identity $identity, Password $password)
    {
        $this->identity = $identity;
        $this->password = $password;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function password(): Password
    {
        return $this->password;
    }
}
