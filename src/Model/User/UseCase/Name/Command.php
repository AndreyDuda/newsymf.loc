<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Name;

class Command
{
    /** @var string */
    public $id;
    /** @var string */
    public $firstName;
    /** @var string */
    public $lastName;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

}