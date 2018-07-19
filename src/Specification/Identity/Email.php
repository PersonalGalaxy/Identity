<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Specification\Identity;

use PersonalGalaxy\Identity\Entity\Identity\Email as Model;
use Innmind\Specification\{
    ComparatorInterface,
    SpecificationInterface,
    CompositeInterface,
    NotInterface,
};

final class Email implements ComparatorInterface
{
    private $value;

    public function __construct(Model $email)
    {
        $this->value = (string) $email;
    }

    /**
     * {@inheritdoc}
     */
    public function property(): string
    {
        return 'email';
    }

    /**
     * {@inheritdoc}
     */
    public function sign(): string
    {
        return '=';
    }

    /**
     * {@inheritdoc}
     */
    public function value()
    {
        return $this->value;
    }

    public function and(SpecificationInterface $specification): CompositeInterface
    {
        // not implemented
    }

    public function or(SpecificationInterface $specification): CompositeInterface
    {
        // not implemented
    }

    public function not(): NotInterface
    {
        // not implemented
    }
}
