<?php

namespace App\Security;

use App\Entity\UserValidation;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class UserValidator
{
    private $group;
    private $mailer;
    private $entityManager;

    public function __construct(GroupRepository $groupRepository, MailerInterface $mailer, EntityManagerInterface $manager)
    {
        $this->group = $groupRepository->find(2);
        $this->mailer = $mailer;
        $this->entityManager = $manager;
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, UserInterface $user, TemplatedEmail $email): void
    {
        $signature = md5(
            $verifyEmailRouteName.
            $user->getId().
            $user->getEmail().
            time()
        );

        $time = new \DateTimeImmutable('NOW');
        $exp = $time->add(new \DateInterval('PT60M'));

        $userValidation = new UserValidation();
        $userValidation
            ->setUser($user)
            ->setSelector('new')
            ->setHash($signature)
            ->setCreatedAt($time)
            ->setExpiresAt($exp)
        ;

        $context = $email->getContext();
        $context['signedUrl'] = $verifyEmailRouteName.'/'.$signature;
        $context['expiresAtMessageData'] = ($exp->format('H:i d.m.Y'));

        $email->context($context);
        $user->setUserGroup($this->group);
        $user->setUserValidation($userValidation);

        $this->mailer->send($email);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(string $hash): bool
    {
        $userValidationRepository = $this->entityManager->getRepository(UserValidation::class);
        $v = $userValidationRepository->findBy(['hash' => $hash])[0] ?? null;
        if (null === $v) {
            return false;
        }

        if ($v->getExpiresAt() < (new \DateTimeImmutable('NOW'))) {
            return false;
        }

        $user = $v->getUser();
        $user->setVerified(true);
        $this->entityManager->persist($user);
        $this->entityManager->remove($v);
        $this->entityManager->flush();

        return true;
    }
}
