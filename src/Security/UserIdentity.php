<?php
declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use App\Model\User\Entity\User\User;

class UserIdentity implements UserInterface
{
    private $id;
    private $username;
    private $password;
    private $role;
    private $status;

    public function __construct(
        string $id,
        string $username,
        string $password,
        string $role
    )
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isActive(): bool
    {
        return $this->status == User::STATUS_ACTIVE;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles()
    {
        $this->role;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {

    }

}