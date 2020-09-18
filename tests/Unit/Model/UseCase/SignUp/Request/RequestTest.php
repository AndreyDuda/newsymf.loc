<?php
declare(strict_types=1);

namespace App\Tests\Unit\Model\UseCase\SignUp\Request;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testSuccess()
    {
        $user = new User(
            $id = Id::next(),
            $date = new \DateTimeImmutable(),
            $email = new Email('test@test.test'),
            $hash = 'hash'
        );

        self::assertEquals($date, $user->getDate());
        self::assertEquals($id, $user->getId());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($hash, $user->getPasswordHash());
    }
}