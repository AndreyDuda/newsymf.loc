<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Model\User\UseCase\SignUp;
use App\ReadModel\User\UserFetcher;
use App\Security\LoginFormAuthenticator;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Contracts\Translation\TranslatorInterface;

class SignUpController extends AbstractController
{
    private $users;
    private $logger;
    private $translator;

    public function __construct(UserFetcher $users, LoggerInterface $logger, TranslatorInterface $translator)
    {
        $this->users = $users;
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @Route("/signup", name="auth.signup")
     * @param Request $request
     * @param SignUp\Request\Handler $handler
     * @return Response
     */
    public function request(Request $request, SignUp\Request\Handler $handler): Response
    {
        $command = new SignUp\Request\Command();

        $form = $this->createForm(SignUp\Request\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Check your email.');
                return $this->redirectToRoute('home');
            } catch (\DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $this->translator->trans($e->getMessage(), [], 'exceptions'));
            }
        }

        return $this->render('app/auth/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param string $token
     * @param SignUp\Confirm\ByToken\Handler $handler
     * @param UserProviderInterface $userProvider
     * @param GuardAuthenticatorHandler $guardHandler
     * @param LoginFormAuthenticator $authenticator
     * @return Response
     */
    public function confirm(
        Request $request,
        string $token,
        SignUp\Confirm\ByToken\Handler $handler,
        UserProviderInterface $userProvider,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator
    ): response
    {
        if (!$user = $this->users->findBySignUpConfirmToken($token)) {
            $this->addFlash('error', 'Incorrect or already confirmed token.');
            return $this->redirectToRoute('auth.signup');
        }


        $command = new SignUp\Confirm\ByToken\Command($token);

        try {
            $handler->handler($command);
           return $guardHandler->authenticateUserAndHandleSuccess(
               $userProvider->loadUserByUsername($user->email),
               $request,
               $authenticator,
               'main'
           );
        } catch (\DomainException $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            $this->addFlash('error', $e->getMessage());
            return  $this->redirectToRoute('auth.signup');
        }
    }

}