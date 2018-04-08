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
use Innmind\Immutable\Set;
use ParagonIE\MultiFactor\{
    OTP\OTPInterface,
    FIDOU2F,
};

final class Identity implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $email;
    private $password;
    private $secretKey;

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
            $this->email,
            $this->secretKey,
            $this->recoveryCodes
        ));

        return $this;
    }

    public function disableTwoFactorAuthentication(): self
    {
        $this->secretKey = null;
        $this->recoveryCodes = null;
        $this->record(new TwoFactorAuthenticationWasDisabled($this->email));

        return $this;
    }

    public function validate(Code $code, OTPInterface $otp = null): bool
    {
        if (!$this->twoFactorAuthenticationEnabled()) {
            throw new LogicException;
        }

        $factor = new FIDOU2F((string) $this->secretKey, $otp);

        if ($factor->validateCode((string) $code)) {
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
        $this->record(new RecoveryCodeWasUsed($this->email));

        return true;
    }

    public function delete(): void
    {
        $this->record(new IdentityWasDeleted($this->email));
    }
}
