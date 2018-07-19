<?php
declare(strict_types = 1);

namespace PersonalGalaxy\Identity\Event\Identity;

use PersonalGalaxy\Identity\Entity\Identity\{
    Identity,
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
        Identity $identity,
        SecretKey $secretKey,
        SetInterface $recoveryCodes
    ) {
        if ((string) $recoveryCodes->type() !== RecoveryCode::class) {
            throw new \TypeError(sprintf(
                'Argument 3 must be of type SetInterface<%s>',
                RecoveryCode::class
            ));
        }

        $this->identity = $identity;
        $this->secretKey = $secretKey;
        $this->recoveryCodes = $recoveryCodes;
    }

    public function identity(): Identity
    {
        return $this->identity;
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
