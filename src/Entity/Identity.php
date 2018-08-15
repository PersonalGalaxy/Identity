<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Entity;

use PersonalGalaxy\Identity\{
    Entity\Identity\Email,
    Entity\Identity\Password,
    Entity\Identity\SecretKey,
    Entity\Identity\RecoveryCode,
    Event\IdentityWasCreated,
    Event\Identity\PasswordWasChanged,
    Event\Identity\TwoFactorAuthenticationWasEnabled,
    Event\Identity\TwoFactorAuthenticationWasDisabled,
    Event\Identity\RecoveryCodeWasUsed,
    Event\IdentityWasDeleted,
    TwoFactorAuthentication\Code,
    Exception\LogicException,
};
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder,
};
use Innmind\TimeContinuum\TimeContinuumInterface;
use Innmind\Immutable\Set;
use ParagonIE\MultiFactor\{
    OTP\TOTP,
    FIDOU2F,
};

final class Identity implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $identity;
    private $email;
    private $password;
    private $secretKey;
    private $recoveryCodes;

    private function __construct(
        Identity\Identity $identity,
        Email $email,
        Password $password
    ) {
        $this->identity = $identity;
        $this->email = $email;
        $this->password = $password;
    }

    public static function create(
        Identity\Identity $identity,
        Email $email,
        Password $password
    ): self {
        $self = new self($identity, $email, $password);
        $self->record(new IdentityWasCreated($identity, $email));

        return $self;
    }

    public function identity(): Identity\Identity
    {
        return $this->identity;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function changePassword(Password $password): self
    {
        $this->password = $password;
        $this->record(new PasswordWasChanged($this->identity));

        return $this;
    }

    public function verify(string $password): bool
    {
        return $this->password->verify($password);
    }

    public function twoFactorAuthenticationEnabled(): bool
    {
        return $this->secretKey instanceof SecretKey;
    }

    public function enableTwoFactorAuthentication(): self
    {
        $this->secretKey = new SecretKey;
        $this->recoveryCodes = Set::of(
            RecoveryCode::class,
            new RecoveryCode,
            new RecoveryCode,
            new RecoveryCode,
            new RecoveryCode,
            new RecoveryCode,
            new RecoveryCode,
            new RecoveryCode,
            new RecoveryCode,
            new RecoveryCode,
            new RecoveryCode
        );
        $this->record(new TwoFactorAuthenticationWasEnabled(
            $this->identity,
            $this->secretKey,
            $this->recoveryCodes
        ));

        return $this;
    }

    public function disableTwoFactorAuthentication(): self
    {
        $this->secretKey = null;
        $this->recoveryCodes = null;
        $this->record(new TwoFactorAuthenticationWasDisabled($this->identity));

        return $this;
    }

    public function validate(Code $code, TimeContinuumInterface $clock): bool
    {
        if (!$this->twoFactorAuthenticationEnabled()) {
            throw new LogicException;
        }

        $factor = new FIDOU2F((string) $this->secretKey, new TOTP);
        $time = (int) ($clock->now()->milliseconds() / 1000);

        if ($factor->validateCode((string) $code, $time)) {
            return true;
        }

        $codes = $this
            ->recoveryCodes
            ->filter(static function(RecoveryCode $recoveryCode) use ($code): bool {
                return $recoveryCode->equals($code);
            });

        if ($codes->size() === 0) {
            return false;
        }

        $this->recoveryCodes = $this->recoveryCodes->remove($codes->current());
        $this->record(new RecoveryCodeWasUsed($this->identity));

        return true;
    }

    public function delete(): void
    {
        $this->record(new IdentityWasDeleted($this->identity));
    }
}
