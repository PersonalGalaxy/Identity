<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Entity\Identity;

use PersonalGalaxy\Identity\TwoFactorAuthentication\Code;
use Innmind\Immutable\Str;

final class RecoveryCode
{
    private $value;

    public function __construct()
    {
        $this->value = (string) Str::of(bin2hex(random_bytes(16)))->substring(0, 8);
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
