<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListenerSubscriber
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function onLexikJwtAuthenticationOnAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof User) {
            if (!$user instanceof UserInterface) {
                return;
            }

            $user = $this->userRepo->findOneBy(['username' => $user->getUsername()]);
        }

        if ($user->getBanned() || false === $user->getVerified()) {
            $message = 'Forbidden';
            $code = 403;
            if ($user->getBanned()) {
                $message = 'User banned';
            }

            if (false === $user->getVerified()) {
                $message = 'User not verified';
                $code = 401;
            }

            $data = [
                'code' => $code,
                'message' => $message,
            ];
            $event->getResponse()->setStatusCode(403);
            $event->setData($data);

            return;
        }

        $data['acp_enabled'] = $user->getAcpEnabled();
        $data['uid'] = $user->getId();
        $data['avatar'] = $user->getAvatar();
        $data['slug'] = $user->getSlug();

        $event->setData($data);
    }
}
