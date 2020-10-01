<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Role;
use App\Model\User\Entity\User\User;
use App\Model\User\Service\PasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    private $hasher;

    public function __construct(PasswordHasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $hasher = $this->hasher->hash('stop2311198');

        $user = User::signupByEmail(
            Id::next(),
            new \DateTimeImmutable(),
            new Email('test@test.com'),
            $hasher,
            'token'
        );

        $user->confirmSignUp();
        $user->changeRole(Role::admin());
        $manager->persist($user);
        $manager->flush();
    }

}