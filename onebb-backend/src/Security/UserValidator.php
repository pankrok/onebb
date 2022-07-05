<?php

namespace App\Security;

use App\Entity\UserValidation;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserValidator
{
    private $group;
    private $mailer;
    private $entityManager;
    private $passwordHasher;

    public function __construct(GroupRepository $groupRepository, MailerInterface $mailer, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->mailer = $mailer;
        $this->entityManager = $manager;
        $this->passwordHasher = $passwordHasher;
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, UserInterface $user, TemplatedEmail $email, string $selector = 'new'): void
    {
        $signature = md5(
            $verifyEmailRouteName.
            $user->getId().
            $user->getEmail().
            time()
        );

        $time = new \DateTimeImmutable('NOW');
        $exp = $time->add(new \DateInterval('PT60M'));
        
        $userValidationRepository = $this->entityManager->getRepository(UserValidation::class);
        $userValidation = $user->getUserValidation();
        
        if ($userValidation === null) {
            $userValidation = new UserValidation();
        }

        if ($userValidation->getSelector() === 'new') {
            $selector = 'new';
        }    
         
        $userValidation
            ->setUser($user)
            ->setSelector($selector)
            ->setHash($signature)
            ->setCreatedAt($time)
            ->setExpiresAt($exp)
        ;

        $context = $email->getContext();
        $context['signedUrl'] = $verifyEmailRouteName.'/'.$signature;
        $context['expiresAtMessageData'] = ($exp->format('H:i d.m.Y'));

        $email->context($context);
        $user->setUserValidation($userValidation);

        $this->mailer->send($email);
        $this->entityManager->persist($userValidation);
    }

    public function handleEmailConfirmation(string $hash): bool
    {
        $userValidationRepository = $this->entityManager->getRepository(UserValidation::class);
        $v = $userValidationRepository->findBy(['hash' => $hash])[0] ?? null;
        if (null === $v) {
            return false;
        }

        if ($v->getExpiresAt() < (new \DateTimeImmutable('NOW')) || $v->getSelector() !== 'new') {
            return false;
        }

        $user = $v->getUser();
        $user->setVerified(true);
        $this->entityManager->persist($user);
        $this->entityManager->remove($v);
        $this->entityManager->flush();

        return true;
    }
    
    public function handleResetPassword(string $hash, string $password): bool
    {
        $userValidationRepository = $this->entityManager->getRepository(UserValidation::class);
        $v = $userValidationRepository->findBy(['hash' => $hash])[0] ?? null;
        if (null === $v) {
            return false;
        }

        if ($v->getExpiresAt() < (new \DateTimeImmutable('NOW')) || $v->getSelector() !== 'pwr') {
            return false;
        }

        $user = $v->getUser();
        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            $password
        ));
        $this->entityManager->persist($user);
        $this->entityManager->remove($v);
        $this->entityManager->flush();

        return true;
    }
}
