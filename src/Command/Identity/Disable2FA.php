<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Command\Identity;

use PersonalGalaxy\Identity\Entity\Identity\Email;

final class Disable2FA
{
    private $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function email(): Email
    {
        return $this->email;
    }
}
