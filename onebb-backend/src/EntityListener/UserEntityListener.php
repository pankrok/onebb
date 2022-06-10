<?php

namespace App\EntityListener;

use App\Entity\User;
use App\Security\UserValidator;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Exercise\HTMLPurifierBundle\HTMLPurifiersRegistryInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserEntityListener
{
    private $passwordHasher;
    private $ti;
    private $slugger;
    private $userValidator;
    private $container;
    private $hp;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        TokenStorageInterface $ti,
        SluggerInterface $slugger,
        UserValidator $userValidator,
        ContainerInterface $container,
        HTMLPurifiersRegistryInterface $registry
    ) {
        $request = Request::createFromGlobals();
        $this->passwordHasher = $passwordHasher;
        $this->pass = $request->request->get('User')['password'] ?? '';
        $this->ti = $ti;
        $this->slugger = $slugger;
        $this->userValidator = $userValidator;
        $this->container = $container;
        $this->hp = $registry->get('default');
    }

    public function prePersist(User $user, LifecycleEventArgs $event)
    {
        $validation = $this->container->getParameter('mail.validation');
        $username = $this->hp->purify($user->getUsername());
        $user
            ->setUsername($username)
            ->computeSlug($this->slugger)
            ->setPlotsNo(0)
            ->setPostsNo(0)
            ->setRoles(['ROLE_USER'])
            ->setVerified(!$validation)
        ;
        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        ));

        if (true === $validation) {
            $this->userValidator->sendEmailConfirmation(
                'verification', $user,
                (new TemplatedEmail())
                    ->from(new Address(
                        $this->container->getParameter('mail.address'),
                        $this->container->getParameter('mail.username')
                    ))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('mail/confirmation_email.html.twig')
            );
        }
    }

    public function preUpdate(User $user, LifecycleEventArgs $event)
    {
        if (null === $this->ti->getToken()) {
            return;
        }

        $currentUser = $this->ti->getToken()->getUser();

        if (!$currentUser instanceof User) {
            exit('{"error": "error"}'); // FIXME
        }

        if ($currentUser->getId() !== $user->getId()) {
            exit('{"error": "unautorise"}'); // FIXME
        }

        $user->computeSlug($this->slugger);
        if ($this->pass) {
            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                $this->pass
            ));
        }
    }
}
