<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\TwoFactorAuthentication;

use PersonalGalaxy\Identity\Exception\DomainException;
use Innmind\Immutable\Str;

final class Code
{
    private $value;

    public function __construct(string $value)
    {
        if (Str::of($value)->empty()) {
            throw new DomainException;
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
