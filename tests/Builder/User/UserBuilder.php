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
        $user = new User(
            $this->id,
            $this->date
        );

        if ($this->email) {
            $user->signupByEmail(
                $this->email,
                $this->hash,
                $this->token
            );
        }

        if ($this->confirmed) {
            $user->confirmSignUp();
        }

        if ($this->network) {
            $user->signUpByNetwork(
                $this->network,
                $this->identity
            );
        }

        return $user;
    }
}