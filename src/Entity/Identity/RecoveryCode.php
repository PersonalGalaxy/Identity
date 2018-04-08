<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Entity\Identity;

use PersonalGalaxy\Identity\TwoFactorAuthentication\Code;

final class RecoveryCode
{
    private $value;

    public function __construct()
    {
        $this->value = random_bytes(16);
    }

    public function equals(Code $code): bool
    {
        return \hash_equals((string) $code, $this->value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
