<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;


use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class Id
{
    private $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
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