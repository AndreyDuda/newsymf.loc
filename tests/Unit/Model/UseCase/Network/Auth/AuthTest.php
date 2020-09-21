<?php
declare(strict_types=1);

namespace App\Tests\Unit\Model\UseCase\Network\Auth;

use App\Model\User\Entity\User\Network;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->build();

        $user->signUpByNetwork(
            $network = 'vk',
            $identity = '0000001'
        );

        self::assertTrue($user->isActive());

        self::assertCount(1, $networks = $user->getNetworks());
        /** @var Network $first */
        self::assertInstanceOf(Network::class, $first = reset($networks));
        self::assertEquals($network, $first->getNetwork());
        self::assertEquals($identity, $first->getIdentity());
    }

    public function testAlready(): void
    {
        $user = (new UserBuilder())->build();

        $user->signUpByNetwork(
            $network = 'vk',
            $identity = '0000001'
        );

        $this->expectExceptionMessage('User is already confirmed.');

        $user->signUpByNetwork($network, $identity);
    }
}