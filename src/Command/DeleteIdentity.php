<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Command;

use PersonalGalaxy\Identity\Entity\Identity\Email;

final class DeleteIdentity
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
