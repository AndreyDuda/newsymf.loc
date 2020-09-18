<?php
declare(strict_types=1);

namespace App\Tests\Unit\Model\UseCase\SignUp\Request;

use App\Model\User\Entity\User\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class RequestTest extends TestCase
{
    public function testSuccess()
    {
        $user = new User(
            $id = Uuid::uuid4()->toString(),
            $date = new \DateTimeImmutable(),
            $email = 'test@app.test',
            $hash = 'hash'
        );

        self::assertEquals($date, $user->getDate());
        self::assertEquals($id, $user->getId());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($hash, $user->getPasswordHash());
    }
}