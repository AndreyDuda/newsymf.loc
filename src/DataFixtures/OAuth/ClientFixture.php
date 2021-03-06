<?php

declare(strict_types=1);

namespace App\DataFixtures\OAuth;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ClientFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $client = new Client('app', 'secret');
        $manager->persist($client);

        $manager->flush();
    }
}