<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_index')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/create', name: 'app_home_create_session')]
    public function createSession(): Response
    {
        return $this->render('home/create_session.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/join', name: 'app_home_join_session')]
    public function joinSession(): Response
    {
        return $this->render('home/join_session.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

//    Now i will need CollaborationController for further  uses




}
