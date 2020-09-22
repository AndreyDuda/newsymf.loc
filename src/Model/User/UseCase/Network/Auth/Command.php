<?php
declare(strict_types=1);

namespace App\Model\User\UseCase\Network\Auth;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Email()
     */
    public $network;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(min="6")
     */
    public $identity;
}