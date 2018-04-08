<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Event\Identity;

use PersonalGalaxy\Identity\Entity\Identity\{
    Email,
    SecretKey,
    RecoveryCode,
};
use Innmind\Immutable\SetInterface;

final class TwoFactorAuthenticationWasEnabled
{
    private $email;
    private $secretKey;
    private $recoveryCodes;

    public function __construct(
        Email $email,
        SecretKey $secretKey,
        SetInterface $recoveryCodes
    ) {
        if ((string) $recoveryCodes->type() !== RecoveryCode::class) {
            throw new \TypeError(sprintf(
                'Argument 3 must be of type SetInterface<%s>',
                RecoveryCode::class
            ));
        }

        $this->email = $email;
        $this->secretKey = $secretKey;
        $this->recoveryCodes = $recoveryCodes;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function secretKey(): SecretKey
    {
        return $this->secretKey;
    }

    /**
     * @return SetInterface<RecoveryCode>
     */
    public function recoveryCodes(): SetInterface
    {
        return $this->recoveryCodes;
    }
}
