<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Security\UserValidator;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;

class ResetPasswordController extends AbstractController
{
    protected $userValidator;
    protected $userRepository; 
    
    public function __construct(UserValidator $userValidator, UserRepository $userRepository)
    {
        $this->userValidator = $userValidator;
        $this->userRepository = $userRepository;
    }
    
    
    #[Route('api/forget-password', name: 'forget-password')]
    public function forget(Request $request, EntityManagerInterface $em): Response
    {
        $email = $request->toArray()['email'];

        $user = $this->userRepository->findOneBy(['email' => $email]);
        
        $this->userValidator->sendEmailConfirmation(
            'reset-password/validation', 
            $user,
            (new TemplatedEmail())
                ->from(new Address(
                    $this->getParameter('mail.address'),
                    $this->getParameter('mail.username')
                ))
                ->to($user->getEmail())
                ->subject('Password reset')
                ->htmlTemplate('mail/lostpw_email.html.twig'),
            'pwr',
        );
        
        $em->flush();
        
        // fixme
        $response = new Response();
        $response->setContent(json_encode([
            'success'
        ]));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
    
    #[Route('api/reset-password', name: 'reset-password')]
    public function resetPassword(Request $request): Response
    {
        $request = $request->toArray();
        
        $password = $request['password'];
        $hash = $request['hash'];
        
        $this->userValidator->handleResetPassword($hash, $password);
        
        // fixme
        $response = new Response();
        $response->setContent(json_encode([
            'success'
        ]));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
}
