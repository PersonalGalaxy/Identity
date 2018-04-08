<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Entity\Identity;

use PersonalGalaxy\Identity\Exception\DomainException;

final class Email
{
    private $value;

    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new DomainException;
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
