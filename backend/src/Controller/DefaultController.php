<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default')]
    public function index(): Response {
        $entityManager = $this->getDoctrine()->getManager();
        return new Response('test');
    }

    #[Route('/test', name: 'test')]
    public function test(): Response {
        return $this->json([
            'message' => 'test!',
        ]);
    }
}