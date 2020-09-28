<?php
declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeTest extends WebTestCase
{
    public function testGuest(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame('http://localhost/login', $client->getResponse()->headers->get('Location'));
    }

    public function testSuccess(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@test.com',
            'PHP_AUTH_PW' => 'stop2311198'
        ]);
        $crawler = $client->request('GET', '/');

        /*$this->assertSame(200, $client->getResponse()->getStatusCode());*/
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertContains('Hello', $crawler->filter('h1')->text());
    }
}