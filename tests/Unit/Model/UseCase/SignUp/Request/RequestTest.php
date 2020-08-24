<?php
declare(strict_types=1);

namespace App\Tests\Unit\Model\UseCase\SignUp\Request;

use App\Model\User\Entity\User;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testSuccess()
    {
        $user = new User(
            $email = 'test@app.test',
            $hash = 'hash'
        );

        self::assertEquals($email, $user->getEmail());
        self::assertEquals($hash, $user->getPasswordHash());
    }
}