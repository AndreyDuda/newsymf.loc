<?php
declare(strict_types=1);

namespace App\Model\User\Service;

use Ramsey\Uuid\Uuid;
use function Webmozart\Assert\Tests\StaticAnalysis\string;

class ConfirmTokenizer
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}