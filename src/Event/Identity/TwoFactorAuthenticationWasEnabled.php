<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Event\Identity;

use PersonalGalaxy\Identity\Entity\Identity\{
    Email,
    SecretKey,
};

final class TwoFactorAuthenticationWasEnabled
{
    private $email;
    private $secretKey;

    public function __construct(Email $email, SecretKey $secretKey)
    {
        $this->email = $email;
        $this->secretKey = $secretKey;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function secretKey(): SecretKey
    {
        return $this->secretKey;
    }
}
