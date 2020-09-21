<?php
declare(strict_types=1);

namespace App\Model\User\Entity\User;

use PHPUnit\Framework\Assert;
use Ramsey\Uuid\Uuid;

class Id
{
    private $value;

    public function __construct(string $value)
    {
        Assert::assertNotEmpty($value);
        $this->value = $value;
    }

    static function next(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function getvalue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}