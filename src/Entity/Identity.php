<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Entity;

use PersonalGalaxy\Identity\{
    Entity\Identity\Email,
    Entity\Identity\Password,
    Event\IdentityWasCreated,
    Event\Identity\PasswordWasChanged,
    Event\IdentityWasDeleted,
};
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder,
};

final class Identity implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $email;
    private $password;

    private function __construct(Email $email, Password $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public static function create(Email $email, Password $password): self
    {
        $self = new self($email, $password);
        $self->record(new IdentityWasCreated($email));

        return $self;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function changePassword(Password $password): self
    {
        $this->password = $password;
        $this->record(new PasswordWasChanged($this->email));

        return $this;
    }

    public function verify(string $password): bool
    {
        return $this->password->verify($password);
    }

    public function delete(): void
    {
        $this->record(new IdentityWasDeleted($this->email));
    }
}
