<?php
declare(strict_types=1);

namespace App\Tests\Unit\Model\UseCase\Reset;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\ResetToken;
use App\Model\User\Entity\User\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ResetTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = $this->buildSignedUpByEmail();

        $now = new \DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->getResetToken());

        $user->passwordReset($now, $hash = 'hash');

        self::assertNotNull($user->getResetToken());
        self::assertEquals($hash, $user->getPasswordHash());
    }

    public function testExpiredToken(): void
    {
        $user = $this->buildSignedUpByEmail();

        $now = new \DateTimeImmutable();
        $token = new ResetToken('token', $now);

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Reset token is expired.');
        $user->passwordReset($now->modify('+1 day'), 'hash');
    }

    public function testNotRequested(): void
    {
        $user = $this->buildSignedUpByEmail();

        $now = new \DateTimeImmutable();

        $this->expectExceptionMessage('Resetting is not requested');
        $user->passwordReset($now, 'hash');
    }

    private function buildSignedUpByEmail(): User
    {
        $user = $this->buildUser();

        $user->signupByEmail(
            new Email('test@test.test'),
            $hash = 'hash',
            $token = 'token'
        );

        return $user;
    }

    private function buildUser(): User
    {
        return new User(
            Id::next(),
            new \DateTimeImmutable()
        );
    }
}