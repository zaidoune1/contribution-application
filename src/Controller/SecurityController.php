<?php

namespace App\Controller;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\RegistrationType;
// use Doctrine\ORM\ ;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{


    public function __construct(UserRepository $userRepository, UrlGeneratorInterface $urlGenerator, UserPasswordHasherInterface $passwordHasher)
    {
        // ...
        $this->passwordHasher = $passwordHasher;
    }

    // ...

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $this->passwordHasher->isPasswordValid($user, $credentials['password']);
    }
    
    #[Route('/connexion', name: 'security.login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('/security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
            // 'user'=>$username,
        ]);
    }

    /**
     * This controller allow us to logout
     *
     * @return void
     */
    #[Route('/deconnexion', 'security.logout')]
    public function logout(){}

    /**
     * This controller allow us to register 
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/inscription', 'security.registration', methods: ['GET', 'POST'])]
    public function registration(Request $request, EntityManagerInterface $manager): Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $this->addFlash(
                'success',
                'Votre compte a bien été créé.'
            );

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security.login');
        }

        return $this->render('/security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
