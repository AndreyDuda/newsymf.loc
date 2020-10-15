<?php

declare(strict_types=1);

namespace App\DataFixtures\Projects;

use App\Model\Work\Entity\Projects\Role\Id;
use App\Model\Work\Entity\Projects\Role\Permission;
use App\Model\Work\Entity\Projects\Role\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixture extends Fixture
{
    public const REFERENCE_MANAGER = 'work_project_role_manager';

    public function load(ObjectManager $manager)
    {
       $guest = $this->createRole('Guest', []);
       $manager->persist($guest);
       $manage = $this->createRole('Manager', [
           Permission::MANAGE_PROJECT_MEMBERS,
       ]);
       $manager->persist($manage);
       $this->setReference(self::REFERENCE_MANAGER, $manage);

       $manager->flush();
    }

    public function createRole(string $name, array $permissions): Role
    {
        return new Role(
            Id::next(),
            $name,
            $permissions
        );
    }

}