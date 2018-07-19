<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Command\Identity;

use PersonalGalaxy\Identity\{
    Entity\Identity\Identity,
    TwoFactorAuthentication\Code,
};

final class Validate2FACode
{
    private $identity;
    private $code;

    public function __construct(Identity $identity, Code $code)
    {
        $this->identity = $identity;
        $this->code = $code;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function code(): Code
    {
        return $this->code;
    }
}
