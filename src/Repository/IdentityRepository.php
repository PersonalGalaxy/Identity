<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Repository;

use PersonalGalaxy\Identity\Entity\Identity;
use Innmind\Immutable\SetInterface;
use Innmind\Specification\SpecificationInterface;


interface IdentityRepository
{
    /**
     * @throws IdentityNotFound
     */
    public function get(Identity\Identity $id): Identity;
    public function add(Identity $identity): self;
    public function remove(Identity\Identity $id): self;
    public function has(Identity\Identity $id): bool;
    public function count(): int;
    /**
     * @return SetInterface<Identity>
     */
    public function all(): SetInterface;
    /**
     * @return SetInterface<Identity>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
