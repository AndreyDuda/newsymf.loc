<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Request;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\SignUpConfirmTokenizer;
use App\Model\User\Service\SignUpConfirmTokenSender;
use App\Model\Flusher;
use App\Model\User\Service\PasswordHasher;

class Handler
{
    /** @var UserRepository */
    private $users;
    /** @var PasswordHasher  */
    private $hasher;
    /** @var Flusher */
    private $flusher;
    /** @var SignUpConfirmTokenSender */
    private $sender;
    /** @var SignUpConfirmTokenizer  */
    private $tokenizer;

    public function __construct(
        UserRepository $users,
        PasswordHasher $hasher,
        SignUpConfirmTokenSender $sender,
        Flusher $flusher,
        SignUpConfirmTokenizer $tokenizer
    )
    {
        $this->users = $users;
        $this->hasher = $hasher;
        $this->sender = $sender;
        $this->flusher = $flusher;
        $this->tokenizer = $tokenizer;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new \DomainException('User already exists.');
        }

        $user = User::signupByEmail(
            Id::next(),
            new \DateTimeImmutable(),
            $email,
            $this->hasher->hash($command->password),
            $token = $this->tokenizer->generate()
        );

        $this->users->add($user);

        $this->sender->send($email, $token);

        $this->flusher->flush();
    }
}