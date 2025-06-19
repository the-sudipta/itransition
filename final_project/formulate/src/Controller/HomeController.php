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
        // Static demo data
        // Generate 9 dummy “forms” with random seeds
        $forms = [];
        for ($i = 1; $i <= 9; $i++) {
            $forms[] = (object)[
                'title'         => "Sample Form #{$i}",
                'description'   => "This is a quick description for form number {$i}.",
                'imageUrl'      => "https://picsum.photos/seed/form{$i}/600/400",
                'likesCount'    => rand(0, 100),
                'commentsCount' => rand(0, 20),
            ];
        }

        return $this->render('home/index.html.twig', [
            'forms' => $forms,
        ]);
    }
}
