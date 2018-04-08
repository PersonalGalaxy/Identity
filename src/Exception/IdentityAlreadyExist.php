<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Exception;

use PersonalGalaxy\Identity\Entity\Identity\Email;

final class IdentityAlreadyExist extends RuntimeException
{
    private $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
        parent::__construct((string) $email);
    }

    public function email(): Email
    {
        return $this->email;
    }
}
