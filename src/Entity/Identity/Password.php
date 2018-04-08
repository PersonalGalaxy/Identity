<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Entity\Identity;

final class Password
{
    private $value;

    public function __construct(string $value)
    {
        $this->value = password_hash($value, PASSWORD_ARGON2I);
    }

    public function verify(string $value): bool
    {
        return password_verify($value, $this->value);
    }
}
