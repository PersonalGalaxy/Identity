<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Command\Identity;

use PersonalGalaxy\Identity\Entity\Identity\{
    Email,
    Password,
};

final class ChangePassword
{
    private $email;
    private $password;

    public function __construct(Email $email, Password $password)
    {
        $this->email = $email;
        $this->password = $password;
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
