<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\Reset;

use App\Model\User\Entity\User\ResetToken;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class ResetTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->viaEmail()->build();

        $now = new \DateTimeImmutable();
        $token = new ResetToken(UserBuilder::TOKEN, $now->modify('+1 day'));

        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->getResetToken());

        $user->passwordReset($now, UserBuilder::HASH);

        self::assertNotNull($user->getResetToken());
        self::assertEquals(UserBuilder::HASH, $user->getPasswordHash());
    }

    public function testExpiredToken(): void
    {
        $user = (new UserBuilder())->viaEmail()->build();

        $now = new \DateTimeImmutable();
        $token = new ResetToken('token', $now);

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Reset token is expired.');
        $user->passwordReset($now->modify('+1 day'), 'hash');
    }

    public function testNotRequested(): void
    {
        $user = (new UserBuilder())->viaEmail()->build();

        $now = new \DateTimeImmutable();

        $this->expectExceptionMessage('Resetting is not requested.');
        $user->passwordReset($now, 'hash');
    }
}