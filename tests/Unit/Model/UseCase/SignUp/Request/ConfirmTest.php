<?php
declare(strict_types=1);

namespace App\Tests\Unit\Model\UseCase\SignUp\Request;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->viaEmail()->build();

        $user->confirmSignUp();

        self::assertFalse($user->isWait());
        self::assertTrue($user->isActive());

        self::assertNull($user->getConfirmToken());
    }

    public function testAlready(): void
    {
        $user = (new UserBuilder())->viaEmail()->confirmed()->build();

        $this->expectExceptionMessage('User is already confirmed.');
        $user->confirmSignUp();
    }

}