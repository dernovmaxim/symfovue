<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AuthController extends AbstractController
{
    #[Route('/auth', name: 'auth')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AuthController.php',
        ]);
    }

    #[Route('/auth/register', name: 'register', methods: ['POST'])]
    public function register(Request $request, UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $password = $request->get('password');
        $email = $request->get('email');
        $user = new User();
        $user->setPassword($userPasswordEncoder->encodePassword($user, $password));
        $user->setEmail($email);

        $payload = [
            'user' => $user->getUsername(),
        ];
        $jwt = JWT::encode($payload, $this->getParameter('jwt_secret'), 'HS256');
        $user->setApiToken($jwt);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
//        return $guardHandler->authenticateUserAndHandleSuccess(
//            $user,
//            $request,
//            $authenticator,
//            'main'
//        );
        return $this->json([
            'email' => $user->getEmail()
        ]);
    }

    #[Route('/auth/login', name: 'login', methods: ['POST'])]
    public function login(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $user = $userRepository->findOneBy(([
            'email' => $request->get('email'),
        ]));

        if (!$user || !$userPasswordEncoder->isPasswordValid($user, $request->get('password'))) {
            return $this->json([
                'message' => 'email or password wrong',
            ]);
        }

        return $this->json([
            'message' => 'success',
            'accessToken' => sprintf('Bearer %s', $user->getApiToken()),
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }


}
