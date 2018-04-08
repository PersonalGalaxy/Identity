<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Repository;

use PersonalGalaxy\Identity\Entity\{
    Identity,
    Identity\Email,
};
use Innmind\Immutable\SetInterface;
use Innmind\Specification\SpecificationInterface;


interface IdentityRepository
{
    /**
     * @throws IdentityNotFound
     */
    public function get(Email $email): Identity;
    public function add(Identity $identity): self;
    public function remove(Email $email): self;
    public function has(Email $email): bool;
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
