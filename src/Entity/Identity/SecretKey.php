<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Entity\Identity;

final class SecretKey
{
    private $value;

    public function __construct()
    {
        $this->value = random_bytes(20);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
