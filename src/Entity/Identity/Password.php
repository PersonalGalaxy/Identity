<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Entity\Identity;

use PersonalGalaxy\Identity\Exception\DomainException;
use Innmind\Immutable\Str;

final class Password
{
    private $value;

    public function __construct(string $value)
    {
        if (Str::of($value)->length() < 8) {
            throw new DomainException;
        }

        $this->value = password_hash($value, PASSWORD_ARGON2I);
    }

    public function verify(string $value): bool
    {
        return password_verify($value, $this->value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
