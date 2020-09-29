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

    /**
     * Command constructor.
     * @param string $network
     * @param string $identity
     */
    public function __construct(string $network, string $identity)
    {
        $this->network = $network;
        $this->identity = $identity;
    }


}