<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Command\Identity;

use PersonalGalaxy\Identity\{
    Entity\Identity\Email,
    TwoFactorAuthentication\Code,
};

final class Validate2FACode
{
    private $email;
    private $code;

    public function __construct(Email $email, Code $code)
    {
        $this->email = $email;
        $this->code = $code;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function code(): Code
    {
        return $this->code;
    }
}
