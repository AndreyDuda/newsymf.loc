<?php

namespace App\Model\User\Entity\User;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User
{
    const STATUS_NEW = 'new';
    const STATUS_WAIT = 'wait';
    const STATUS_ACTIVE = 'active';

    /**
     * @var Id
     */
    private $id;

    /** @var \DateTimeImmutable */
    private $date;

    /**
     * @var Email
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $passwordHash;

    /**
     * @var string|null
     */
    private $confirmToken;

    /**
     * @var string
     */
    private $status;

    /**
     * @var Network[]|ArrayCollection
     */
    private $networks;

    /**
     * @var ResetToken|null
     */
    private $resetToken;

    public function __construct(Id $id, \DateTimeImmutable $date)
    {
        $this->id = $id;
        $this->date = $date;
        $this->status = self::STATUS_NEW;
        $this->networks = new ArrayCollection();
    }

    public function signupByEmail(Email $email, string $hash, string $token): void
    {
        if (!$this->isNew()) {
            throw new \DomainException('User is already signed up.');
        }

        $this->email = $email;
        $this->passwordHash = $hash;
        $this->confirmToken = $token;
        $this->status = self::STATUS_WAIT;
    }

    public function confirmSignUp(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already confirmed.');
        }
        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

    public function signUpByNetwork(string $network, string $identity): void
    {
        if (!$this->isNew()) {
            throw new \DomainException('User is already confirmed.');
        }
        $this->attachNetwork($network, $identity);
        $this->status = self::STATUS_ACTIVE;
    }

    private function attachNetwork(string $network, string $identity): void
    {
        foreach ($this->networks as $existing) {
            if ($existing->isForNetwork($network)) {
                throw new \DomainException('Network is already attached.');
            }
        }
        $this->networks->add(new Network($this, $network, $identity));
    }

    public function requestPasswordReset(ResetToken $token, \DateTimeImmutable $date): void
    {
        if (!$this->email) {
            throw new \DomainException('Email is not specified.');
        }
        if ($this->resetToken && !$this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Resetting is already requested.');
        }
        $this->resetToken = $token;
    }

    public function passwordReset(\DateTimeImmutable $date, string $hash): void
    {
        if (!$this->resetToken) {
            throw new \DomainException('Resetting is not requested.');
        }
        if ($this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Reset token is expired.');
        }
        $this->passwordHash = $hash;
    }

    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getDate():\DateTimeImmutable
    {
        return $this->date;
    }

    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    public function getNetworks(): array
    {
        return $this->networks->toArray();
    }

    public function getResetToken()
    {
        return $this->resetToken;
    }
}
