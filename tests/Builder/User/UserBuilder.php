<?php

declare(strict_types=1);

namespace App\Tests\Builder\User;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class UserBuilder
{
    const NETWORK = 'vk';
    const IDENTITY = '000001';
    const EMAIL = 'test@test.test';
    const HASH = 'hash';
    const TOKEN = 'token';

    private $id;
    private $date;

    private $email;
    private $hash;
    private $token;
    private $confirmed;

    private $network;
    private $identity;

    public function __construct()
    {
        $this->id = Id::next();
        $this->date = new \DateTimeImmutable();
    }

    public function viaEmail(Email $email = null, string $hash = null, string $token = null): self
    {
        $clone = clone $this;
        $clone->email = $email ?? new Email(self::EMAIL);
        $clone->hash = $hash ?? self::HASH;
        $clone->token = $token ?? self::TOKEN;
        return $clone;
    }

    public function confirmed(): self
    {
        $clone = clone $this;
        $clone->confirmed = true;
        return $clone;
    }

    public function viaNetwork(string $network = null, string $identity = null): self
    {
        $clone = clone $this;
        $clone->network = $network ?? self::NETWORK;
        $clone->identity = $identity ?? self::IDENTITY;
        return $clone;
    }

    public function build(): User
    {
        if ($this->email) {
            $user = User::signUpByEmail(
                $this->id,
                $this->date,
                $this->email,
                $this->hash,
                $this->token
            );

            if ($this->confirmed) {
                $user->confirmSignUp();
            }

            return $user;
        }

        if ($this->network) {
            return User::signUpByNetwork(
                $this->id,
                $this->date,
                $this->network,
                $this->identity
            );
        }

        throw new \BadMethodCallException('Specify via method.');
    }
}