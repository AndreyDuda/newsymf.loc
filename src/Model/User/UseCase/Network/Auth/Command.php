<?php
declare(strict_types=1);

namespace App\Model\User\UseCase\Network;

class Command
{
    /**
     * @var string
     */
    public $network;

    /**
     * @var string
     */
    public $identity;
}