<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Event\Identity;

use PersonalGalaxy\Identity\Entity\Identity\Email;

final class TwoFactorAuthenticationWasEnabled
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
