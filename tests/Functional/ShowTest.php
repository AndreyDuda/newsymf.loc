<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Model\User\Entity\User\Id;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShowTest extends WebTestCase
{
    public function testGuest(): void
    {
        $client = static::createClient();
        $client->request('GET', '/users/' . UsersFixture::EXISTING_ID);

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame('http://localhost/login', $client->getResponse()->headers->get('Location'));
    }

    public function testUser(): void
    {
        $client = static::createClient([], AuthFixture::userCredentials());
        $client->request('GET', '/users/' . UsersFixture::EXISTING_ID);

        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testGet(): void
    {
        $client = static::createClient([], AuthFixture::adminCredentials());
        $crawler = $client->request('GET', '/users/' . UsersFixture::EXISTING_ID);

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Users', $crawler->filter('title')->text());
        $this->assertStringContainsString('Show User', $crawler->filter('table')->text());
    }

    public function testNotFound(): void
    {
        $client = static::createClient([], AuthFixture::adminCredentials());
        $client->request('GET', '/users/' . Id::next()->getValue());

        $this->assertSame(404, $client->getResponse()->getStatusCode());
    }
}