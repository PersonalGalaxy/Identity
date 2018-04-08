<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Exception;

use PersonalGalaxy\Identity\TwoFactorAuthentication\Code;

final class Invalid2FACode extends RuntimeException
{
    private $twoFactorAuthenticationCode;

    public function __construct(Code $code)
    {
        $this->twoFactorAuthenticationCode = $code;
        parent::__construct((string) $code);
    }

    public function code(): Code
    {
        return $this->twoFactorAuthenticationCode;
    }
}
